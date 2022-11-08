<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\User;

class ELearningCourseInstruction extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'course_id',
        'instruction_id',
        'audio_url'
    ];
    
    public function course()
    {
        return $this->belongsTo(ELearningCourse::class, 'course_id');
    }
    
    public function instruction()
    {
        return $this->belongsTo(ELearningInstruction::class, 'instruction_id');
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
        self::creating(function (ELearningCourseInstruction $model) {
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