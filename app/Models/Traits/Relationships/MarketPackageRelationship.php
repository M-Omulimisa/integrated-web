<?php

namespace App\Models\Traits\Relationships;

use App\Models\Market\MarketPackagePricing;
use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackageEnterprise;

/**
 * Class MarketPackageRelationship.
 */
trait MarketPackageRelationship
{
    public function enterprises()
    {
        return $this->hasMany(MarketPackageEnterprise::class, 'package_id');
    }

    public function pricing()
    {
        return $this->hasMany(MarketPackagePricing::class, 'package_id');
    }

    public function messages()
    {
        return $this->hasMany(MarketPackageMessage::class, 'package_id');
    }
}
