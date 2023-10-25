<?php

namespace App\Models\Organisations;
  
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\OrganisationUserPositionRelationship;
  
class OrganisationUserPosition extends BaseModel
{
    use Uuid, OrganisationUserPositionRelationship;
  
    protected $fillable = [
        'user_id', 'position_id'
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
        self::creating(function (OrganisationUserPosition $model) {
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
