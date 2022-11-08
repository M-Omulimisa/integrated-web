<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\Relationships\Users\UserRelationship;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Authenticatable implements AuthenticatableContract
{
    use HasFactory, Notifiable, HasRoles, UserRelationship, Uuid, HasApiTokens;

    protected $connection = 'mysql';

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
            $model->id = $model->generateUuid();
            $model->password = Hash::make($model->password);
            $model->created_by = auth()->user()->id ?? null;
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
        $this->update(['last_login_at' => Carbon::now() ]);
    }

    public function routeNotificationForSlack($notification)
    {
        return env('LOG_SLACK_WEBHOOK_URL');
    }

    public function swap($reset=false)
    {
        if ($reset) {
            // set hash value to null
            $hash = NULL;
        }
        else{
            // set hash value
            $hash = bcrypt(auth()->user()->getKey().microtime());
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
}