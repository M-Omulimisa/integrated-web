<?php

namespace App\Models\Farmers;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\FarmerGroupEnterpriseRelationship;
  
class FarmerGroupEnterprise extends BaseModel
{
    use Uuid, FarmerGroupEnterpriseRelationship;
  
    protected $fillable = [
        'farmer_group_id', 'enterprise_id'
    ];

    public const CTRY_UG    = "Uganda";
    public const CTRY_GH     = "Ghana";
    public const CTRY_ZM     = "Zambia";

    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function (FarmerGroupEnterprise $model) {
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
