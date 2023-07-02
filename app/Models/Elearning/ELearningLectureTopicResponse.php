<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningLectureTopicResponse extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'lecture_topic_id',
        'comment',
        'video_url',
        'audio_url',
        'document_url',
        'user_id',
        'student_id',
        'owner_has_listened'
    ]; 

    public function topic()
    {
        return $this->belongsTo(ELearningLectureTopic::class, 'lecture_topic_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasLiked()
    {
        return ELearningLectureTopicResponseLike::where('lecture_topic_response_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function likes()
    {
        return $this->hasMany(ELearningLectureTopicResponseLike::class, 'lecture_topic_response_id')->orderBy('id', 'DESC');
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
        self::creating(function (ELearningLectureTopicResponse $model) {
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