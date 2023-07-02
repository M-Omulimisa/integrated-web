<?php

namespace App\Models\Training;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\TtrainingTopicRelationship;
  
class TrainingTopic extends BaseModel
{
    use Uuid, TtrainingTopicRelationship;
  
    protected $fillable = [
        'topic', 'country_id', 'organisation_id', 'status', 'user_id', 'details'
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
        self::creating(function (TrainingTopic $model) {
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
