<?php

namespace App\Models\Traits\Relationships;

use App\Models\RegionModel;
use App\Models\Market\MarketPackage;

/**
 * Class MarketPackageRegionRelationship.
 */
trait MarketPackageRegionRelationship
{
    public function region()
    {
        return $this->belongsTo(RegionModel::class, 'region_id');
    }

    public function peckage()
    {
        return $this->belongsTo(MarketPackage::class, 'package_id');
    }
}
