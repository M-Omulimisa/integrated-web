<?php

namespace App\Models\Market;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketPackageEnterpriseRelationship;
  
class MarketPackageEnterprise extends BaseModel
{
    use Uuid, MarketPackageEnterpriseRelationship;
  
    protected $fillable = [
        'package_id',
        'enterprise_id',
    ];

    /**
     * every time a model is created
     * automatically assign a UUID to it
     *
     * @var array
     */
    

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
   

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    
}
