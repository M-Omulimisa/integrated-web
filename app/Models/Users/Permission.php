<?php

namespace App\Models\Users;

use App\Models\Users\Permission;
use Spatie\Permission\Models\Permission as BasePermission;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Permission extends BasePermission
{
	use Uuid;

    /**
     * every time a model is created,
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (Permission $model) {
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