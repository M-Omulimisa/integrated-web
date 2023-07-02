<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Type extends BaseModel
{
    use LogsActivity, Uuid;

    protected $fillable = [
        'name', 'alias'
    ];
    protected static $logAttributes = [
        'name', 'alias'
    ];

    public static function findByAlias(string $alias)
    {
        return self::query()->whereAlias($alias)->first();
    }

    /**
     * every time a model is created, we want to automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (Type $model) {
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
