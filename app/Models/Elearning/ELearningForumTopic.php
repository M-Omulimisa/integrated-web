<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningForumTopic extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'course_id',
        'subject',
        'description',
        'video_url',
        'audio_url',
        'document_url',
        'user_id',
        'status',
        'student_id'
    ]; 
    
    public function course()
    {
        return $this->belongsTo(ELearningCourse::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(ELearningForumTopicResponse::class, 'forum_topic_id')->orderBy('id', 'DESC');
    }

    public function subscriptions()
    {
        return $this->hasMany(ELearningForumTopicSubscription::class, 'forum_topic_id')->orderBy('id', 'DESC');
    }

    public function likes()
    {
        return $this->hasMany(ELearningForumTopicLike::class, 'forum_topic_id')->orderBy('id', 'DESC');
    }

    public function hasSuscribed()
    {
        return ELearningForumTopicSubscription::where('forum_topic_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function hasLiked()
    {
        return ELearningForumTopicLike::where('forum_topic_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }
    
    public function student()
    {
        return $this->belongsTo(ELearningStudent::class, 'student_id');
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
        self::creating(function (ELearningForumTopic $model) {
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