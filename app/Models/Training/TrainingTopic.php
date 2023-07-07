<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\Organisations\Organisation;
use App\Models\Settings\Country;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\TtrainingTopicRelationship;
use App\Models\User;
use Exception;

class TrainingTopic extends BaseModel
{
    use Uuid, TtrainingTopicRelationship;

    protected $fillable = [
        'topic', 'country_id', 'organisation_id', 'status', 'user_id', 'details'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subs()
    {
        return $this->hasMany(TrainingSubtopic::class, 'topic_id');
    }
    public function trainings()
    {
        return $this->hasMany(Training::class);
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
        self::creating(function (TrainingTopic $model) {
            $model->id = $model->generateUuid();
            $org = Organisation::find($model->organisation_id);
            if ($org == null) {
                throw new Exception("Org not found.", 1);
            }
            $model->country_id = $org->country_id;
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
