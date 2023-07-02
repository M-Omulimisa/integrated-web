<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Enterprise;
use App\Models\Market\MarketPackage;

/**
 * Class MarketPackageEnterpriseRelationship.
 */
trait MarketPackageEnterpriseRelationship
{
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function peckage()
    {
        return $this->belongsTo(MarketPackage::class, 'package_id');
    }
}
