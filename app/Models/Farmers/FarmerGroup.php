<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\Traits\Relationships\FarmerGroupRelationship;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class FarmerGroup extends BaseModel
{
    use Uuid,FarmerGroupRelationship;

    protected $fillable = [
        'name',
        'country_id',
        'organisation_id',
        'code',
        'address',
        'group_leader',
        'group_leader_contact',
        'establishment_year',
        'registration_year',
        'meeting_venue',
        'meeting_days',
        'meeting_time',
        'meeting_frequency',
        'location_id',
        'last_cycle_savings',
        'registration_certificate',
        'latitude',
        'longitude',
        'status',
        'created_by_user_id',
        'created_by_agent_id',
        'agent_id'
    ];

    /**
     * every time a model is created 
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (FarmerGroup $model) {
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

    //getter for meeting_venue 
    public function getMeetingVenueAttribute($value)
    {
        return Farmer::where('farmer_group_id', $this->id)->count();
    }
}
