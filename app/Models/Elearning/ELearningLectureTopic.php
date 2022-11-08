<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningLectureTopic extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'lecture_id',
        'subject',
        'description',
        'video_url',
        'audio_url',
        'document_url',
        'user_id',
        'status',
        'student_id'
    ]; 
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function lecture()
    {
        return $this->belongsTo(ELearningLecture::class, 'lecture_id');
    }

    public function responses()
    {
        return $this->hasMany(ELearningLectureTopicResponse::class, 'lecture_topic_id')->orderBy('id', 'DESC');
    }

    public function subscriptions()
    {
        return $this->hasMany(ELearningLectureTopicSubscription::class, 'lecture_topic_id')->orderBy('id', 'DESC');
    }

    public function likes()
    {
        return $this->hasMany(ELearningLectureTopicLike::class, 'lecture_topic_id')->orderBy('id', 'DESC');
    }

    public function hasSuscribed()
    {
        return ELearningLectureTopicSubscription::where('lecture_topic_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function hasLiked()
    {
        return ELearningLectureTopicLike::where('lecture_topic_id', $this->id)->where('user_id', auth()->user()->id)->first();
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
        self::creating(function (ELearningLectureTopic $model) {
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