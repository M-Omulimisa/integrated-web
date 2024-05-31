<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\Organisations\Organisation;
use App\Models\Settings\Country;
use App\Models\Settings\Language;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;
use App\Models\User;
use App\Models\Utils;
use Illuminate\Support\Facades\DB;

class Farmer extends BaseModel
{


    use Uuid, FarmerRelationship;

    //table farmer
    protected $table = 'farmers';


    protected static function boot()
    {
        parent::boot();
        self::creating(function (Farmer $model) {

            $count = Farmer::where([])->count();
            $model->id = ($count + 1);
            $phone_number = Utils::prepare_phone_number($model->phone);
            if (Utils::phone_number_is_valid($phone_number)) {
                $exist = Farmer::where('phone', $phone_number)->first();
                if ($exist) {
                    return false; 
                    throw new \Exception("Farmer with phone number " . $phone_number . " already exists. Please use a different phone number.");
                }
            }
 
            //get last id
            $f = Farmer::orderBy('id', 'desc')->first();
            if ($f) {
                $model->id = $f->id + 1;
            } else {
                $model->id = 1;
            } 
        });
     /*    self::updating(function (Farmer $model) {
            //$model->id = $model->generateUuid();
            //prcess account
        }); */

        //udpated
        self::updated(function (Farmer $model) {
            return;
            try {
                self::process($model);
            } catch (\Throwable $th) {
                //throw $th;
            }
        });

        self::created(function (Farmer $model) {
            return true; 
            try {
                self::process($model);
            } catch (\Throwable $th) {
                //throw $th;
            }

            $_phone = Utils::prepare_phone_number($model->phone);
            if (Utils::phone_number_is_valid($_phone)) {
                $model->phone = $_phone;
                $last_name = $model->last_name;
                $app_download_link = 'bit.ly/4aM24Ea'; 

                $msg = "Hello, your M-Omulimisa account has been created successfully! Download the app from this link $app_download_link and login using your phone number and password 4321. Thank you!";                
                try {
                    Utils::send_sms($_phone, $msg);
                } catch (\Throwable $th) {
                    //throw $th;
                }

                $email = $model->email;

                $data['body'] = $model->body;
                //$data['view'] = 'mails/mail-1';
                $data['data'] = $data['body'];
                $data['name'] = $last_name;
                $data['email'] = $email;
                $data['subject'] = $model->title . ' - M-Omulimisa';
                try {
                    Utils::mail_sender($data);
                    $model->save();
                } catch (\Throwable $th) {
                }
            }
        });
    }

    //prcess
    public static function process($m)
    {
        if ($m->is_processed == 'Yes') {
            return;
        }
        $set = ' user_account_processed = "Yes" ';

        $phone_number = $m->phone_number;
        if (strlen($phone_number) < 6) {
            $phone_number = $m->phone;
        }

        $phone_number = Utils::prepare_phone_number($phone_number);

        if (!Utils::phone_number_is_valid($phone_number)) {
            $set .= ', process_status = "Failed" ';
            $set .= ', error_message = "Invalid phone number ' . $phone_number . '" ';
            $sql = "UPDATE farmers SET $set WHERE id = $m->id";
            DB::update($sql);
            return;
        }

        //get user with same phone number
        $user = User::where('phone_number', $phone_number)->first();
        if ($user == null) {
            $user = User::where('phone', $phone_number)->first();
        }
        if ($user) {
            $set .= ', process_status = "Failed" ';
            $set .= ', error_message = "User with phone number ' . $phone_number . ' already exists" ';
            $sql = "UPDATE farmers SET $set WHERE id = $m->id";
            DB::update($sql);
            return;
        }
        $user = new User();
        $user->name = $m->first_name . ' ' . $m->last_name;
        $user->phone = $phone_number;
        $user->username = $phone_number;
        $user->phone_number = $phone_number;
        if (Utils::email_is_valid($m->email)) {
            $user->email = $m->email;
        } else {
            $user->email = $phone_number;
        }
        $user->password = password_hash('4321', PASSWORD_DEFAULT);
        $user->created_by = 1;
        $user->country_id = 1;
        $user->organisation_id = '57159775-b9e0-41ce-ad99-4fdd6ed8c1a0';
        $user->status = 'Active';
        $user->verified = 1;
        $user->email_verified_at = date('Y-m-d H:i:s');
        $user->reg_date = date('Y-m-d H:i:s');
        $user->first_name = $m->first_name;
        $user->last_name = $m->last_name;
        $user->sex = $m->sex;
        $user->nin = $m->national_id_number;
        $user->district_id = $m->district_id;
        $user->subcounty_id = $m->subcounty_id;
        $user->parish_id = $m->parish_id;
        $user->village = $m->village;
        $user->language_id = $m->language_id;

        if (strlen(trim($user->name)) < 3) {
            $user->name = $user->phone;
        }

        try {
            $user->save();
        } catch (\Throwable $th) {
            $set .= ', process_status = "Failed" ';
            $set .= ', error_message = "Unable to create user account because : " ';
            $sql = "UPDATE farmers SET $set WHERE id = $m->id";
            DB::update($sql);
            return;
        }
    }
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function farmer_group()
    {
        return $this->belongsTo(FarmerGroup::class, 'farmer_group_id');
    }
}
