<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use App\Models\Traits\Translate\TranslatedNameTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Status extends BaseModel
{
    use TranslatedNameTrait, Uuid;
    
    protected $appends = ['translated_name'];

    protected $fillable = ['name', 'type', 'class'];

    public static function findByNameAndType($name, $type = 'user')
    {
        return self::query()
            ->where('name', $name)
            ->where('type', $type)
            ->first();
    }

    protected static function boot()
    {
        parent::boot();

        /**
         * every time a model is created, we want to automatically assign a UUID to it
         *
         * @var array
         */
        self::creating(function (Status $model) {
            $model->id = $model->generateUuid();
        });

        static::saved(function ($status) {
            cache()->forget('statuses');
            cache()->forget('statuses-'.optional($status)->type);
        });

        static::deleting(function ($status) {
            cache()->forget('statuses');
            cache()->forget('statuses-'.optional($status)->type);
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
