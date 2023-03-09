<?php

namespace App\Models\Traits\Relationships;

use App\Models\Weather\WeatherSubscription;
use App\Models\Market\MarketSubscription;
use App\Models\Insurance\InsuranceSubscription;

/**
 * Class SubscriptionPaymentRelationship.
 */
trait SubscriptionPaymentRelationship
{

    public function weather_subscription()
    {
        return $this->belongsTo(WeatherSubscription::class, 'weather_subscription_id');
    }

    public function market_subscription()
    {
        return $this->belongsTo(MarketSubscription::class, 'market_subscription_id');
    }

    public function insurance_subscription()
    {
        return $this->belongsTo(InsuranceSubscription::class, 'insurance_subscription_id');
    }
}
