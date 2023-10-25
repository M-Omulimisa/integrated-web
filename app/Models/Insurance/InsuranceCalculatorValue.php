<?php

namespace App\Models\Insurance;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\InsuranceCalculatorRelationship;
  
class InsuranceCalculatorValue extends BaseModel
{
    use Uuid, InsuranceCalculatorRelationship;
  
    protected $fillable = [
        'country_id',
        'season_id',
        'sum_insured',
        'sum_insured_special',
        'govt_subsidy_none',
        'govt_subsidy_small_scale',
        'govt_subsidy_large_scale',
        'location_level_id',
        'govt_subsidy_locations',
        'scale_limit',
        'ira_levy',
        'vat',
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
        self::creating(function (InsuranceCalculatorValue $model) {
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
