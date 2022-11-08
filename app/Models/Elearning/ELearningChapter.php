<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;

class ELearningChapter extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'course_id',
        'title',
        'summary',
        'parent_id',
        'user_id',
        'status',
        'numbering',
        'start_date',
        'end_date'
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

    public function lectures()
    {
        return $this->hasMany(ELearningLecture::class, 'chapter_id');
    }

    public function assignments()
    {
        return $this->hasMany(ELearningAssignment::class, 'chapter_id');
    }

    public function topics()
    {
        return $this->hasMany(ELearningChapter::class, 'parent_id');
    }

    public function ch_parent()
    {
        return $this->belongsTo(ELearningChapter::class, 'parent_id');
    }

    public function unansweredQuestions()
    {
        $result = ELearningLectureTopic::whereIn('lecture_id',function($query) {
                $query->select('id')->where('chapter_id', $this->id)->from('e_learning_lectures');
            })->whereNotIn('id',function($query){
                $query->select('lecture_topic_id')->from('e_learning_lecture_topic_responses');
            })->count();

        return $result;
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
        self::creating(function (ELearningChapter $model) {
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