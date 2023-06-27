<?php

namespace App\Models\Ussd;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class UssdSessionData extends BaseModel
{
    use Uuid;

    protected $connection = 'mysql';
    
    protected $fillable = [
            'session_id',
            'phone_number',

            // Market subscription columns
            'module',
            'market_subscrption_for',
            'market_subscriber',
            'market_package_id',
            'market_language_id',
            'market_frequency',
            'market_frequency_count',
            'market_confirmation',
            'market_payment_status',
            'market_currency',
            'market_cost',

            'insurance_subscrption_for',
            'insurance_subscriber',
            // 'insurance_subscriber_name',
            'insurance_district',
            'insurance_district_id',
            //--- 'insurance_subcounty',
            //--- 'insurance_subcounty_id',
            //--- 'insurance_parish',
            //--- 'insurance_parish_id',
            'insurance_season_id',
            'insurance_enterprise_id',
            'insurance_acreage',
            'insurance_sum_insured',
            'insurance_premium',
            'insurance_confirmation',
            'insurance_payment_status',

            'confirmation_message'
        ];

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (UssdSessionData $model) {
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
