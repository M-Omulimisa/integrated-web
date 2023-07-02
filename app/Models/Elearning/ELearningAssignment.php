<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Traits\MP3File;

class ELearningAssignment extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'course_id',
        'chapter_id',
        'title',
        'video_url',
        'audio_url',
        'document_url',
        'user_id',
        'status',
        'numbering',
        'type',
        'answer',
    ];

    public const status         = [
        1 => "Available", 
        0 => "Unavailable"
    ];

    public function course()
    {
        return $this->belongsTo(ELearningCourse::class, 'course_id');
    }

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
        return false;
        // return ELearningAssignmentResponse::where('chapter_id', $this->chapter->id)->where('student_id', auth()->user()->id)->first();
    }

    public function answers()
    {
        return $this->hasMany(ELearningAssignmentResponse::class, 'assignment_id')->orderBy('id', 'DESC');
    }

    public function studentsAttemptedQuestion()
    {
        return ELearningAssignmentResponse::select('student_id')->where('assignment_id', $this->id)->groupBy('student_id')
            ->whereIn('assignment_id',function($query){
                $query->select('id')->from('e_learning_assignments');
            })->get();
    }

    public function studentsPassedQuestion()
    {
        $attempts = $this->studentsAttemptedQuestion();

        $pass = 0;
        if (count($attempts) > 0) {
            foreach ($attempts as $student) {
                $response = ELearningAssignmentResponse::where('assignment_id', $this->id)->where('student_id', $student->student_id)->first();
                if ($response->answer == $this->answer) {
                    $pass = 0 + 1;
                }
            }
        }

        return $pass;
    }

    public function studentsFailedQuestion()
    {
        $attempts = $this->studentsAttemptedQuestion();

        $fail = 0;
        if (count($attempts) > 0) {
            foreach ($attempts as $student) {
                $response = ELearningAssignmentResponse::where('assignment_id', $this->id)->where('student_id', $student->student_id)->first();
                if ($response->answer != $this->answer) {
                    $fail = 0 + 1;
                }
            }
        }

        return $fail;
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
        self::creating(function (ELearningAssignment $model) {
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