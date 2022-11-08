<?php

namespace App\Models\Elearning;

use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerRelationship;

use Spatie\Permission\Models\Role;
use App\Models\User;

class ELearningAnnouncement extends BaseModel
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
        'start_date',
        'end_date',
        'display_days',
        'body'
            ];

    public const status         = [
        1 => "Visible", 
        0 => "Invisible"
    ];

    public function course()
    {
        return $this->belongsTo(ELearningCourse::class, 'course_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasBeenRead()
    {
        return ELearningAnnouncementView::where('announcement_id', $this->id)->where('user_id', auth()->user()->id)->first();
    }

    public function views()
    {
        return $this->hasMany(ELearningAnnouncementView::class, 'announcement_id');
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
        self::creating(function (ELearningAnnouncement $model) {
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