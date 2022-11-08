<?php

namespace App\Models\Loans;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\OutputPriceRelationship;
  
class BuyerOutputPrice extends BaseModel
{
    use Uuid, OutputPriceRelationship;
  
    protected $fillable = [
        'project_id',
        'season_id',
        'buyer_id',
        'enterprise_id',
        'enterprise_variety_id',
        'enterprise_type_id',
        'currency_id',
        'price',
        'start_date',
        'end_date',
        'unit_id'
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
        self::creating(function (BuyerOutputPrice $model) {
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
