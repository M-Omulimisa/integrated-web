<?php

namespace App\Models\Settings;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\CountryProviderRelationship;
  
class CountryProvider extends BaseModel
{
    use Uuid, CountryProviderRelationship;
  
    protected $fillable = [
        'country_id',
        'name',
        'codes',
        'payment_api',
        'sms_api'
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
        self::creating(function (CountryProvider $model) {
            $model->id = $model->generateUuid();
            $model->codes = str_replace(' ', '', $model->codes);
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
