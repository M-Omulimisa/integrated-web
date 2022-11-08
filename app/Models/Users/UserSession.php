<?php

namespace App\Models\Users;

use App\Models\User;
use Carbon\Carbon;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

/**
 * Class UserSession
 * @property string $id
 * @property string $user_number
 * @property string $token
 * @property int $expires
 * @property string $device
 * @property Carbon $created_at
 * @package App
 */
class UserSession extends BaseModel
{
    use Uuid;

    protected $connection = 'mysql';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
        'token',
        'expires',
        'status',
        'created_at'
    ];

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (UserSession $model) {
            $model->id = $model->generateUuid();
            $model->created_at = Carbon::now();
            // $userSession->expires = auth('web')->factory()->getTTL() * config('session.lifetime');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
