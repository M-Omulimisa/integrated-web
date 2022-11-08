<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use App\Models\User;

class ELearningIncomingCall extends BaseModel
{
    use Uuid;
    protected $fillable = [
        'callSessionState',
        'direction',
        'callerCountryCode',
        'durationInSeconds',
        'amount',
        'callerNumber',
        'destinationNumber',
        'callerCarrierName',
        'status',
        'sessionId',
        'callStartTime',
        'isActive',
        'currencyCode',

        'course_id',
        'student_id',
        'call_back_student',
        'called_back_at',
        'call_back_failure',
        'call_back_failed_at'
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
        self::creating(function (ELearningIncomingCall $model) {
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