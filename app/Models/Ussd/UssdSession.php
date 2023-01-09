<?php

namespace App\Models\Ussd;

use App\Models\User;
use Carbon\Carbon;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

/**
 * Class UssdSession
 * @property string $id
 * @property string $user_number
 * @property string $token
 * @property int $expires
 * @property string $device
 * @property Carbon $created_at
 * @package App
 */

class UssdSession extends BaseModel
{
    use Uuid;

    protected $connection = 'mysql';
    
    protected $fillable = [
            'session_id',
            'phone_number',
            'last_menu'
        ];

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (UssdSession $model) {
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
}

