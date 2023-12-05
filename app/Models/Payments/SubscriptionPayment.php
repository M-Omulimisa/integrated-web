<?php

namespace App\Models\Payments;
  
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\SubscriptionPaymentRelationship;
  
class SubscriptionPayment extends BaseModel
{
    use Uuid, SubscriptionPaymentRelationship;
  
    protected $fillable = [
        'tool',
        'weather_session_id',
        'market_session_id',
        'insurance_session_id',
        'method',
        'provider',
        'account',
        'reference_id',
        'reference',
        'amount',
        'status',
        'details',
        'error_message',
        'narrative',
        'payment_api',
        'sms_api'
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
        self::creating(function (SubscriptionPayment $model) {
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
