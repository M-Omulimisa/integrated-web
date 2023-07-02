<?php

namespace App\Models\Loans;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\YieldEstimationRelationship;
  
class YieldEstimation extends BaseModel
{
    use Uuid, YieldEstimationRelationship;
  
    protected $fillable = [
        'enterprise_variety_id',
        'farm_size',
        'farm_size_unit_id',
        'input_estimate',
        'input_unit_id',
        'output_min_estimate',
        'output_max_estimate',
        'output_unit_id'
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
        self::creating(function (YieldEstimation $model) {
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
