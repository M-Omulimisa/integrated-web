<?php

namespace App\Models\Farmers;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;
  
class Farmer extends BaseModel
{
    use Uuid, FarmerRelationship;
  
    protected $fillable = [

        'organisation_id',
        'farmer_group_id',            
        'first_name',
        'last_name',
        'country_id',
        'language_id',
        'national_id_number',
        'gender',
        'education_level',
        'year_of_birth',
        'email',
        'phone',
        'is_your_phone',
        'is_mm_registered',
        'other_economic_activity',
        'location_id',
        'address',
        'latitude',
        'longitude',

        'farming_scale',
        'land_holding_in_acres',
        'land_under_farming_in_acres',           
        'ever_bought_insurance',
        'ever_received_credit',

        'added_at',
        'status',
        
        'photo',
        'id_photo_front',
        'id_photo_back',
        'created_by_user_id',
        'created_by_agent_id',
        'agent_id'
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
        self::creating(function (Farmer $model) {
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
