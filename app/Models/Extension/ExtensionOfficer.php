<?php

namespace App\Models\Extension;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\ExtensionOfficerRelationship;
  
class ExtensionOfficer extends BaseModel
{
    use Uuid, ExtensionOfficerRelationship;
  
    protected $fillable = [
        'organisation_id', 
        'extension_officer_id',
        'position_id', 
        'name',
        'phone',
        'email',
        'category',
        'gender',
        'education_level',
        'country_id',
        'location_id',
        'address',
        'latitude',
        'longitude',
        'created_by',
        'password',
        'status',
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
        self::creating(function (ExtensionOfficer $model) {
            $model->id = $model->generateUuid();
            $model->created_by = auth()->user()->id;
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
