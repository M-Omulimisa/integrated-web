<?php

namespace App\Models\Traits\Relationships;

use App\Models\Market\MarketPackagePricing;
use App\Models\Market\MarketPackageMessage;
use App\Models\Market\MarketPackageEnterprise;;
use App\Models\Market\MarketPackageRegion;
use App\Models\Market\MarketSubscription;
use App\Models\Ussd\UssdSessionData;
use App\Models\Settings\Enterprise;

/**
 * Class MarketPackageRelationship.
 */
trait MarketPackageRelationship
{
    public function ents()
    {
        return $this->belongsToMany(Enterprise::class, 'market_package_enterprises', 'package_id', 'enterprise_id');
    }

    public function pricing()
    {
        return $this->hasMany(MarketPackagePricing::class, 'package_id');
    }

    public function messages()
    {
        return $this->hasMany(MarketPackageMessage::class, 'package_id');
    }

    public function last_message()
    {
        return $this->hasOne(MarketPackageMessage::class, 'package_id')->whereNotNull('message')->latest('updated_at');
    }

    public function regions()
    {
        return $this->hasMany(MarketPackageRegion::class, 'package_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(MarketSubscription::class, 'package_id');
    }

    public function ussd_sessions()
    {
        return $this->hasMany(UssdSessionData::class, 'market_package_id');
    }
}
