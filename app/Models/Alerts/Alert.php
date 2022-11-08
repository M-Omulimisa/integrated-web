<?php

namespace App\Models\Alerts;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\AlertRelationship;
  
class Alert extends BaseModel
{
    use Uuid, AlertRelationship;
  
    protected $fillable = [
        'message', 'is_to_users', 'is_to_farmers', 'is_village_agents', 'is_extension_officers', 'is_scheduled', 'date', 'time', 'status', 'user_id', 'country_id'
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
        self::creating(function (Alert $model) {
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
