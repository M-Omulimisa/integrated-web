<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;

class ELearningCourse extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'title',
        'summary',
        'description',
        'content',
        'audience',
        'outcomes',
        'user_id',
        'image_banner',
        'video_url',
        'about_certificates',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'duration_in_days',
        'duration_in_weeks',
        'team',
        'operations',
        'logo',
        'brochure',
        'status',
        'read_only_mode',
        'enrollment_status',
        'code',
        'lecture_type',
        'certificate_url',
        'status_archived_at',
        'enrollment_closed_at'
    ];

    public const status         = [
        "Open" => "Open", 
        "Closed" => "Closed"
    ];
    public const enrollment_status    = [
        "Current" => "Current", 
        "Upcoming" => "Upcoming",
        "Archieved" => "Archieved", 
    ];
    public const lecture_types         = [
        "Topical"   => "Topical", 
        // "Weekly"    => "Weekly "
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userRegisteredForThisCourse($userId)
    {
        return ELearningCourseRegistration::where('user_id', $userId)->where('course_id', $this->id)->orderBy('id','DESC')->limit(1)->first();
    }

    public function userHasAnnouncementSubscription()
    {
        return ELearningAnnouncementSubscription::where('course_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function userHasResourceSubscription()
    {
        return ELearningResourceSubscription::where('course_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function chapters()
    {
        return $this->hasMany(ELearningChapter::class, 'course_id')->orderBy('id', 'DESC');
    }


    // Reports

    public function enrolledStudents()
    {
        return ELearningStudentEnrollment::where('course_id', $this->id)->whereNull('removed_at')->get();
    }

    public function forums()
    {
        return $this->hasMany(ELearningForumTopic::class, 'course_id')->orderBy('id', 'DESC');
    }

    public function announcements()
    {
        return $this->hasMany(ELearningAnnouncement::class, 'course_id')->orderBy('id', 'DESC');
    }

    public function resources()
    {
        return $this->hasMany(ELearningResource::class, 'course_id')->orderBy('id', 'DESC');
    }

    public function lectures()
    {
        return ELearningLecture::whereIn('chapter_id',function($query){
                $query->select('id')->where('course_id', $this->id)->from('e_learning_chapters');
            })->get();
    }

    public function lecture_quiz_questions()
    {
        return ELearningAssignment::whereIn('chapter_id',function($query){
                $query->select('id')->where('course_id', $this->id)->from('e_learning_chapters');
            })->get();
    }

    public function lecture_general_questions()
    {
        return $this->hasMany(ELearningGeneralAssignment::class, 'course_id')->orderBy('id', 'DESC');
    }

    public function questions()
    {
        return ELearningLectureTopic::whereIn('lecture_id',function($query){
                $query->select('id')->from('e_learning_lectures')
                        ->whereIn('chapter_id',function($query){
                                $query->select('id')->where('course_id', $this->id)->from('e_learning_chapters');
                            });
            })->get();
    }

    public function unansweredQuestions()
    {
        return ELearningLectureTopic::whereIn('lecture_id',function($query){
                $query->select('id')->from('e_learning_lectures')
                        ->whereIn('chapter_id',function($query){
                                $query->select('id')->where('course_id', $this->id)->from('e_learning_chapters');
                            });
            })->whereNotIn('id',function($query){
                $query->select('lecture_topic_id')->from('e_learning_lecture_topic_responses');
            })->get();
    }

    public function answeredQuestions()
    {
        return ELearningLectureTopic::whereIn('lecture_id',function($query){
                $query->select('id')->from('e_learning_lectures')
                        ->whereIn('chapter_id',function($query){
                                $query->select('id')->where('course_id', $this->id)->from('e_learning_chapters');
                            });
            })->whereIn('id',function($query){
                $query->select('lecture_topic_id')->from('e_learning_lecture_topic_responses');
            })->get();
    }

    public function text_message($numbering)
    {
        $default_message = ELearningMessage::where('numbering', $numbering)->first();

        if ($default_message) {
            if ($default_message->isSetInCourse($this->id)){
                return $default_message->isSetInCourse($this->id)->text_message;
            }else{
                return $default_message->default_message;
            }
        }else{
           return null; 
        }
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
        self::creating(function (ELearningCourse $model) {
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