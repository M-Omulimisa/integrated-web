<?php

namespace App\Models\Insurance;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\InsuranceLossRelationship;
  
class InsuranceFarmerCompensation extends BaseModel
{
    use Uuid, InsuranceLossRelationship;
  
    protected $fillable = [
        'subscription_id',
        'farmer_phone',
        'api_provider',
        'api_request_status',
        'provider_code',
        'transfer_account_username',
        'transaction_id',
        'refrence_id',
        'transaction_status',
        'transaction_error_message',
        'transaction_amount',
        'initiated_by_user_id',
        'initiation_approved_by_phonenumber',
        'initiation_approved_by_email',
        'approved_status',
        'authorities',
        'api_id'
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
        self::creating(function (InsuranceLossValue $model) {
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
