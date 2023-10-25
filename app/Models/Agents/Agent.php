<?php

namespace App\Models\Agents;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\AgentRelationship;
  
class Agent extends BaseModel
{
    use Uuid, AgentRelationship;
  
    protected $fillable = [
        'organisation_id', 
        'agent_id', 
        'name',
        'national_id_number',
        'gender',
        'phone',
        'is_mm_phone',
        'mm_phone',
        'email',
        'country_id',
        'location_id',
        'address',
        'latitude',
        'longitude',
        'password',
        'user_id',
        'status',
        'category',
        
        'photo',
        'id_photo_front',
        'id_photo_back'
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
        self::creating(function (Agent $model) {
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
