<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\MeasureUnit;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Market\MarketPackage;

/**
 * Class LocationRelationship.
 */
trait EnterpriseRelationship
{

    public function unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }

    public function varieties()
    {
        return $this->hasMany(EnterpriseVariety::class, 'enterprise_id');
    }

    public function packages()
    {
        return $this->belongsToMany(MarketPackage::class, 'market_package_enterprises', 'enterprise_id', 'package_id');
    }
}
