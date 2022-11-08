<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

class ELearningVoiceMenu extends BaseModel
{
    use Uuid;
    protected $fillable = [ 
        'session_id',
        'phone_number',
        'main_action',

        'student_id',
        'course_id',
        'course_type',

        'lesson_action',

        'previous_week_id',
        'current_week_id',
        'next_week_id',

        'previous_chapter_id',
        'current_chapter_id',
        'next_chapter_id',

        'previous_lecture_id',
        'current_lecture_id',
        'next_lecture_id',

        'previous_question_id',
        'current_question_id',
        'next_question_id',

        'status',

        'chapter_has_assignment',
        'course_has_assignment',

        'previous_response_id',
        'current_response_id',
        'next_response_id'
    ];
    
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
        self::creating(function (ELearningVoiceMenu $model) {
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

