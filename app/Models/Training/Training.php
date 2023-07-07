<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\TrainingSession;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\CountryRelationship;

class Training extends BaseModel
{
    use Uuid, CountryRelationship;

    protected $fillable = [
        'village_agent_id',
        'extension_officer_id',
        'user_id',
        'subtopic_id',
        'training_topic_id',
        'details',
        'date',
        'time',
        'venue',
        'location_id',
        'status',
        'latitude',
        'longitude'
    ];


    public function training_topic()
    {
        return $this->belongsTo(TrainingTopic::class, 'training_topic_id');
    }

    public function sub_topics()
    {
        return $this->hasMany(TrainingSubtopic::class);
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class);
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
        self::creating(function (Training $model) {
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
