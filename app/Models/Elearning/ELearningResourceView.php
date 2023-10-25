<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningResourceView extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'resource_id',
        'user_id'
    ]; 

    public function resource()
    {
        return $this->belongsTo(ELearningResource::class, 'resource_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (ELearningResourceView $model) {
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