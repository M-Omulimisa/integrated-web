<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\LanguageRelationship;
use Exception;

class Language extends BaseModel
{
    use Uuid, LanguageRelationship;

    protected $fillable = [
        'country_id', 'name'
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
        self::creating(function (Language $model) {
            $model->id = $model->generateUuid();
        });

        //deleting
        self::deleting(function (Language $model) {
            throw new Exception("Deleting not allowed.");
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
