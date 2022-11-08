<?php

namespace App\Models\Extension;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\ExtensionPositionRelationship;
  
class ExtensionOfficerPosition extends BaseModel
{
    use Uuid, ExtensionPositionRelationship;
  
    protected $fillable = [
        'organisation_id',
        'name',            
        'admin_level',
        'user_id',
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
        self::creating(function (ExtensionOfficerPosition $model) {
            $model->id = $model->generateUuid();
            $model->user_id = auth()->user()->id;
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
