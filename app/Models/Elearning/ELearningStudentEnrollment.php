<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\Settings\Subcounty;
use App\Models\Settings\District;
use App\Models\Organisation;
use App\Models\User;

class ELearningStudentEnrollment extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'course_id',
        'student_id',
        'added_by',
        'removed_at',
        'status'
    ]; 

    public function course()
    {
        return $this->belongsTo(ELearningCourse::class, 'course_id');
    }

    public function student()
    {
        return $this->belongsTo(ELearningStudent::class, 'student_id');
    } 
    
    public function user()
    {
        return $this->belongsTo(User::class, 'added_by');
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
        self::creating(function (ELearningStudentEnrollment $model) {
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