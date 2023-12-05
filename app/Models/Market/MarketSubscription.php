<?php

namespace App\Models\Market;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketSubscriptionRelationship;
  
class MarketSubscription extends BaseModel
{
    use Uuid, MarketSubscriptionRelationship;
  
    protected $fillable = [
        'farmer_id',
        'language_id',
        // 'location_id',
        'region_id',
        'first_name',
        'last_name',
        'email',
        'frequency',
        'period_paid',
        'start_date',
        'end_date',
        'status',
        'user_id',
        'outbox_generation_status',
        'outbox_reset_status',
        'outbox_last_date',
        'seen_by_admin',
        'trial_expiry_sms_sent_at',
        'trial_expiry_sms_failure_reason',
        'renewal_id',
        'organisation_id',
        'package_id',
        'payment_id',
        'phone'
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
        self::creating(function (MarketSubscription $model) {
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
