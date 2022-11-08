<?php

namespace App\Models\Weather;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\WeatherOutboxRelationship;
  
class WeatherOutbox extends BaseModel
{
    use Uuid, WeatherOutboxRelationship;

    protected $table = 'weather_outbox';
  
    protected $fillable = [
        'subscription_id',
        'farmer_id',
        'recipient',
        'message',
        'status',
        'statuses',
        'failure_reason',
        'processsed_at',
        'sent_at',
        'failed_at',
        'sent_via'
    ];


    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (WeatherOutbox $model) {
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
