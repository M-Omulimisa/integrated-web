<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Traits\MP3File;

class ELearningGeneralAssignment extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'course_id',
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
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function lecture_length()
    {
        $mp3file = new MP3File(public_path().'/uploads/courses/'.$this->audio_url);
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
        return $this->hasMany(ELearningGeneralAssignmentResponse::class, 'assignment_id')->orderBy('id', 'DESC');
    }

    public function studentsAttemptedGeneralQuestion()
    {
        return ELearningGeneralAssignmentResponse::select('student_id')->where('assignment_id', $this->id)->groupBy('student_id')
            ->whereIn('assignment_id',function($query){
                $query->select('id')->from('e_learning_general_assignments');
            })->get();
    }

    public function studentsPassedGeneralQuestion()
    {
        $attempts = $this->studentsAttemptedGeneralQuestion();

        $pass = 0;
        if (count($attempts) > 0) {
            foreach ($attempts as $student) {
                $response = ELearningGeneralAssignmentResponse::where('assignment_id', $this->id)->where('student_id', $student->student_id)->first();
                if ($response->answer == $this->answer) {
                    $pass = 0 + 1;
                }
            }
        }

        return $pass;
    }

    public function studentsFailedGeneralQuestion()
    {
        $attempts = $this->studentsAttemptedGeneralQuestion();

        $fail = 0;
        if (count($attempts) > 0) {
            foreach ($attempts as $student) {
                $response = ELearningGeneralAssignmentResponse::where('assignment_id', $this->id)->where('student_id', $student->student_id)->first();
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
        self::creating(function (ELearningGeneralAssignment $model) {
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