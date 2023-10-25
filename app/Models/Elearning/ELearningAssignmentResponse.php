<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;

class ELearningAssignmentResponse extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'student_id',
        'course_id',
        'chapter_id',
        'assignment_id',
        'answer',
        'student_id'
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
    
    public function student()
    {
        return $this->belongsTo(ELearningStudent::class, 'student_id');
    }
    
    public function assignment()
    {
        return $this->belongsTo(ELearningAssignment::class, 'assignment_id');
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
        self::creating(function (ELearningAssignmentResponse $model) {
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