<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;
use App\Models\User;

class ELearningInactiveStudentsCallSetting extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'calling_time',
        'retry_after_in_minutes',
        'number_of_retries',
        'make_missed_after_in_minutes',
        'calls_per_cycle'
    ]; 


    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (ELearningInactiveStudentsCallSetting $model) {
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