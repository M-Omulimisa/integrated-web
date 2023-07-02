<?php

namespace App\Models\Users;

use App\Models\User;
use App\Models\Settings\Type;
use Spatie\Activitylog\Models\Activity as BaseActivity;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Activity extends BaseActivity
{
	use Uuid; 

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (Activity $model) {
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'causer_id' => 'string',
        'subject_id' => 'string'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    // activity()->log('Viewed permissions');
        /*activity()
           ->performedOn(new Permission)
           ->causedBy(auth()->user())
           ->withProperties(['customProperty' => 'customValue'])
           ->log('Viewed permissions');*/
}