<?php

namespace App\Models\Loans;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\CountryRelationship;
  
class LoanRequestFarmer extends BaseModel
{
    use Uuid, CountryRelationship;
  
    protected $fillable = [
        'loan_request_id',
            'farmer_id',
            'size_of_land',
            'enterprise_variety_id',
            'input_estimation',
            'output_estimation',
            'price_per_unit',
            'input_quantity',
            'estimated_output_quantity',
            'total_input_amount',
            'insurance_amount',            
            'user_id',
            'village_agent_id',
            'by_farmer_id',
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
        self::creating(function (LoanRequestFarmer $model) {
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
