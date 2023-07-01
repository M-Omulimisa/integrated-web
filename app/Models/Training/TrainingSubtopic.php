<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\TrainingSubtopicRelationship;

class TrainingSubtopic extends BaseModel
{
    use Uuid, TrainingSubtopicRelationship;

    protected $fillable = [
        'topic_id', 'title', 'type', 'status', 'details', 'training_id'
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
        self::creating(function (TrainingSubtopic $model) {
            $model->id = $model->generateUuid();
        });
    }
    public function topic()
    {
        return $this->belongsTo(TrainingTopic::class, 'topic_id');
    }

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
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
