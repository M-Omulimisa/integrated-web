<?php

namespace App\Models\Auth;

use App\Models\BaseModel;
// use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class OneTimePasswordActivity extends BaseModel
{
    use Uuid;

    protected $connection = 'mysql';

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (OneTimePasswordActivity $model) {
            $model->id = $model->generateUuid();
        });
    }

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

    protected $fillable = [
    	"user_id", 
    	"otp_id", 
    	"phone", 
    	"type", 
    	'sent_at', 
    	'send_failed_at', 
    	'verified_at', 
    	'discarded_at', 
    	'deleted_at'
    ];
}