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
                    throw new \Exception("Farmer with phone number " . $phone_number . " already exists. Please use a different phone number.");
                }
            }
        });
        self::updating(function (Farmer $model) {
            //$model->id = $model->generateUuid();
        });

        self::created(function (Farmer $model) {

            $_phone = Utils::prepare_phone_number($model->phone);
            if (Utils::phone_number_is_valid($_phone)) {
                $model->phone = $_phone;
                $last_name = $model->last_name;

                $msg = "Dear " . $last_name . ",\nWelcome to the Molimisa App. Your account has been created successfully. You can now access the app using your phone number and password: 4321. Thank you for choosing us.";
                try {
                    Utils::send_sms($_phone, $msg);
                } catch (\Throwable $th) {
                    //throw $th;
                }

                $email = $model->email;

                $data['body'] = $this->body;
                //$data['view'] = 'mails/mail-1';
                $data['data'] = $data['body'];
                $data['name'] = $last_name;
                $data['email'] = $email;
                $data['subject'] = $this->title . ' - M-Omulimisa';
                try {
                    Utils::mail_sender($data);
                    $this->save();
                } catch (\Throwable $th) {
                }
            }
        });
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
