<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;
use App\Models\User;

class ELearningInactiveStudentsOutgoingCall extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'callSessionState',
        'callerNumber',
        'sessionId',

        'course_id',
        'student_id',

        'call_student',
        'called_at',
        'call_failure',
        'call_failed_at',
        'call_back_at'
    ]; 
    
    public function student()
    {
        return $this->belongsTo(ELearningStudent::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(ELearningCourse::class, 'course_id');
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
        self::creating(function (ELearningInactiveStudentsOutgoingCall $model) {
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