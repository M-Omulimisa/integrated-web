<?php

namespace App\Models\Farmers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class FarmerSpecification extends Model
{

    use Uuid;

    protected $fillable = ['country_id', 'farmer_specification', 'html_representation', 'field_type', 'description', 'is_mandatory'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function (Farmer $model) {
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