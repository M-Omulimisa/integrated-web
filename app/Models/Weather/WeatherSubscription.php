<?php

namespace App\Models\Weather;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\WeatherSubscriptionRelationship;
  
class WeatherSubscription extends BaseModel
{
    use Uuid, WeatherSubscriptionRelationship;
  
    protected $fillable = [
        'language_id',
        'location_id',
        'first_name',
        'last_name',
        'email',
        'frequency',
        'period_paid',
        'start_date',
        'end_date',
        'paying_account',
        'payment_amount',
        'payment_confirmation',
        'reference_id',
        'payment_reference',
        'payment_status',
        'payment_provider', //AIRTEL, MTN, 
        'payment_method', //Mobile Money or Bank
        'payment_details',
        'payment_failure_reason',
        'status',
        'user_id',
        'outbox_generation_status',
        'outbox_reset_status',
        'outbox_last_date',
        'awhere_field_id',
        'seen_by_admin',
        'trial_expiry_sms_sent_at',
        'trial_expiry_sms_failure_reason',
        'renewal_id',
        'organisation_id',
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
        self::creating(function (WeatherSubscription $model) {
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
