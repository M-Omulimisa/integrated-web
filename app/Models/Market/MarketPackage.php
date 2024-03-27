<?php

namespace App\Models\Market;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Models\Traits\Relationships\MarketPackageRelationship;
  
class MarketPackage extends BaseModel
{
    use Uuid, MarketPackageRelationship;
  
    protected $fillable = [
        'country_id',
        'name',
        'menu',
        'status'
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

        self::creating(function (MarketPackage $model) {
            $model->id = $model->generateUuid();
        });

        static::deleted(function () {


            $market_packages =  MarketPackage::orderBy('created_at', 'asc')->get();

            $counter = 1;
            foreach($market_packages as $package){

                $package->update(['menu' => $counter]);

                $counter++;

            }



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

    public function getPackageLanguageDetail($language_id, $param)
    {
        $package_language = MarketPackageMessage::wherePackageId($this->id)
                                                ->whereLanguageId($language_id)
                                                ->first();
        return $package_language->$param ?? null;
    }

    public function getPackagePricingDetail($frequency, $param)
    {
        $package_frequency = MarketPackagePricing::wherePackageId($this->id)
                                                ->whereFrequency($frequency)
                                                ->first();
        return $package_frequency->$param ?? null;
    }

    public function getPackageEnterpriseDetail($enterprise_id, $param)
    {
        $package_enterprise = MarketPackageEnterprise::wherePackageId($this->id)
                                                ->whereEnterpriseId($enterprise_id)
                                                ->first();
        return $package_enterprise->$param ?? null;
    }

    public function getPackageRegionDetail($region_id, $param)
    {
        $package_region = MarketPackageRegion::wherePackageId($this->id)
                                                ->whereRegionId($region_id)
                                                ->first();
        return $package_region->$param ?? null;
    }

    public function getEnterprisesAttribute($enterprises)
    {

        return json_decode($enterprises, true);
       
    }

    public function setEnterprisesAttribute($enterprises)
    {
        if(!empty($enterprises)){

            $this->attributes['enterprise'] = json_encode($enterprises);
        }
 
    }

    //has many subcriptions
    public function subscriptions()
    {
        return $this->hasMany(MarketSubscription::class, 'package_id');
    } 
}
