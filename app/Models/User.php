<?php

namespace App\Models;

use App\Models\Organisations\Organisation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\Relationships\Users\UserRelationship;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Administrator implements AuthenticatableContract, JWTSubject
{
    use HasFactory, Notifiable, HasRoles, UserRelationship, HasApiTokens;

    protected $connection = 'mysql';


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function roles(): BelongsToMany
    {
        $pivotTable = config('admin.database.role_users_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'photo',
        'password',
        'password_last_updated_at',
        'last_login_at',
        'status',
        'created_by',
        'verified',
        'email_verified_at',
        'country_id',
        'banned_until',
        'organisation_id',
        'microfinance_id',
        'invitation_token',
        'two_auth_method',
        'user_hash',
        'distributor_id',
        'buyer_id'
    ];

    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.users_table'));

        parent::__construct($attributes);
    }


    public const STATUS_INACTIVE   = "Inactive";
    public const STATUS_ACTIVE     = "Active";
    public const STATUS_SUSPENDED  = "Suspended";
    public const STATUS_BANNED     = "Banned";
    public const STATUS_INVITED    = "Invited";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (User $model) {
            //$model->id = $model->generateUuid(); 
            //$model->created_by = auth()->user()->id ?? null;

            if ($model->phone != null && strlen($model->phone) > 3) {
                $model->phone = Utils::prepare_phone_number($model->phone);
                if (!Utils::phone_number_is_valid($model->phone)) {
                    throw new \Exception("Invalid phone number " . $model->phone, 1);
                }
                //check if phone number is already registered
                $user = User::where('phone', $model->phone)->first();
                if ($user != null) {
                    throw new \Exception("Phone number already registered.", 1);
                }
            }
            $model->username = $model->email;
        });

        //updating
        self::updating(function (User $model) {
            //$model->updated_by = auth()->user()->id ?? null;
            if ($model->phone != null && strlen($model->phone) > 3) {
                $model->phone = Utils::prepare_phone_number($model->phone);
                if (!Utils::phone_number_is_valid($model->phone)) {
                    throw new \Exception("Invalid phone number " . $model->phone, 1);
                }
                //check if phone number is already registered
                $user = User::where('phone', $model->phone)->first();
                if ($user != null && $user->id != $model->id) {
                    //throw new \Exception("Phone number already registered.", 1);
                }
            }
            //if email not empty and not null set username to email
            if ($model->email != null && strlen($model->email) > 3) {
                $model->username = $model->email;
            }
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'id' => 'string'
    ];

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function setLastLogin()
    {
        $this->update(['last_login_at' => Carbon::now()]);
    }

    public function routeNotificationForSlack($notification)
    {
        return env('LOG_SLACK_WEBHOOK_URL');
    }

    public function swap($reset = false)
    {
        if ($reset) {
            // set hash value to null
            $hash = NULL;
        } else {
            // set hash value
            $hash = bcrypt(auth()->user()->getKey() . microtime());
            \Session::put('userhash', $hash);
        }

        $this->user_hash = $hash;
        $this->save();
    }

    /**
     * Get the active OTP for the given user
     *
     * @param App\User $user
     * @return \tpaksu\LaravelOTPLogin\OneTimePassword
     */
    public function getUserMobileOTP()
    {
        return \App\Models\Mobile\MobileAppOneTimePassword::whereUserId($this->id)->where("status", "!=", "discarded")->first();
    }

    //get dropdown list of users
    public static function getDropDownList($conds)
    {
        $users = User::where($conds)->get();
        $list = [];
        foreach ($users as $user) {
            $list[$user->id] = $user->name;
            //check if phone number is set
            if ($user->phone != null && strlen($user->phone) > 3) {
                $list[$user->id] .= " (" . $user->phone . ")";
            }
        }
        return $list;
    }

    //send password reset link
    public function sendPasswordReset()
    {


        $email = $this->email;
        //check if mail is not valid using filter
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = $this->username;
        }

        $token = rand(100000, 999999);
        $this->reset_password_token = $token;
        $this->save();

        $phone_num = $this->phone;
        //prepare
        $phone_num = Utils::prepare_phone_number($phone_num);
        //validate
        if (!Utils::phone_number_is_valid($phone_num)) {
            $phone_num = $this->phone_number;
            //prepare
            $phone_num = Utils::prepare_phone_number($phone_num);
            //validate
            if (!Utils::phone_number_is_valid($phone_num)) {
                $phone_num = null;
            }
        }

        if (Utils::phone_number_is_valid($phone_num)) {
            $sms_message = "Dear {$this->name}, You have requested to reset your password. Please use the TOKEN below to reset your password. {$token}";
            try {
                Utils::send_sms($phone_num, $sms_message);
            } catch (\Throwable $th) {
            }
        }

        $link = url('password-reset-link?tok=' . $token);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail_message = <<<EOT
            <p>Dear {$this->name},</p>
            <p>You have requested to reset your password. Please use the TOKEN below to reset your password.</p>
            <p><strong>{$token}</strong></p>
            <p>Alternatively, you can click on the link below to reset your password.</p>
            <p><a href="{$link}">{$link}</a></p>
            <p>Thank you.</p>
            EOT;

            $data['body'] = $mail_message;
            //$data['view'] = 'mails/mail-1';
            $data['data'] = $data['body'];
            $data['name'] = $this->name;
            $data['email'] = $email;
            $data['subject'] = 'Password Reset ' . ' - M-Omulimisa';
            try {
                Utils::mail_sender($data);
            } catch (\Throwable $th) {
            }
        }
    }
}
