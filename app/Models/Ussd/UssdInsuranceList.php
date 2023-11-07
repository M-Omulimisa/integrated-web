<?php

namespace App\Models\Ussd;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class UssdInsuranceList extends BaseModel
{
    use Uuid;

    protected $connection = 'mysql';
    
    protected $fillable = [
            'ussd_session_data_id',
            'insurance_enterprise_id',
            'insurance_acreage',
            'insurance_sum_insured',
            'insurance_premium',
        ];

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (UssdInsuranceList $model) {
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

