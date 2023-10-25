<?php

namespace App\Models\Market;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\WeatherConditionRelationship;
  
class MarketKeywordInitiatedPrice extends BaseModel
{
    use Uuid, WeatherConditionRelationship;
  
    protected $fillable = [
        'digit',
        'category',
        'position',
        'language_id',
        'description',
        'constraints',
        'user_id'
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
        self::creating(function (MarketKeywordInitiatedPrice $model) {
            $model->id = $model->generateUuid();
            $model->user_id = auth()->user()->id;
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
