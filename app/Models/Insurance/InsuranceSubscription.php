<?php

namespace App\Models\Insurance;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\InsuranceSubscriptionRelationship;
  
class InsuranceSubscription extends BaseModel
{
    use Uuid, InsuranceSubscriptionRelationship;
  
    protected $fillable = [
        'agent_id',
        'agent_phone',
        'farmer_id',
        // 'location_id',
        'district_id',
        'subcounty_id',
        'parish_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'calculator_values_id',
        'season_id',
        'enterprise_id',
        'acreage',
        'sum_insured',
        'premium',
        'status',
        'user_id',
        'organisation_id',
        'seen_by_admin'
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
        self::creating(function (InsuranceSubscription $model) {
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
