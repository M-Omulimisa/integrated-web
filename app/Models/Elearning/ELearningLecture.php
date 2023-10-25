<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Traits\MP3File;

class ELearningLecture extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'chapter_id',
        'title',
        'video_url',
        'audio_url',
        'document_url',
        'user_id',
        'status',
        'numbering'
    ];

    public const status         = [
        1 => "Available", 
        0 => "Unavailable"
    ];

    public function chapter()
    {
        return $this->belongsTo(ELearningChapter::class, 'chapter_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function lecture_length()
    {
        $mp3file = new MP3File(public_path().'/uploads/courses/'.$this->audio_url);//http://www.npr.org/rss/podcast.php?id=510282
        $duration2 = $mp3file->getDuration();//(slower) for VBR (or CBR)
        return MP3File::formatTime($duration2);
    }

    public function hasBeenWatched()
    {
        return ELearningLectureAttendance::where('lecture_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function discussions()
    {
        return $this->hasMany(ELearningLectureTopic::class, 'lecture_id')->orderBy('id', 'DESC');
    }

    public function unansweredQuestions()
    {
        $result = ELearningLectureTopic::whereIn('lecture_id',function($query) {
                $query->select('id')->where('id', $this->id)->from('e_learning_lectures');
            })->whereNotIn('id',function($query){
                $query->select('lecture_topic_id')->from('e_learning_lecture_topic_responses');
            })->count();

        return $result;
    }

    public function visitsperlecture()
    {
        return ELearningLectureVisit::where('lecture_id', $this->id)->count();
    }

    public function attendancesperlecture()
    {
        return ELearningLectureAttendance::where('lecture_id', $this->id)->count();
    }

    public function studentsVisitedLecture()
    {
        return count(ELearningLectureVisit::select('student_id')->where('lecture_id', $this->id)->groupBy('student_id')->get());
    }

    public function studentsVisitedRepetitvely()
    {
        return count(ELearningLectureVisit::select('student_id', 'lecture_id')->where('lecture_id', $this->id)->groupBy('student_id','lecture_id')->havingRaw('COUNT(*) > 1')->get());
    }

    public function studentsVisitedDistinctly()
    {
        return count(ELearningLectureVisit::select('student_id', 'lecture_id')->where('lecture_id', $this->id)->groupBy('student_id','lecture_id')->havingRaw('COUNT(*) = 1')->get());
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
        self::creating(function (ELearningLecture $model) {
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