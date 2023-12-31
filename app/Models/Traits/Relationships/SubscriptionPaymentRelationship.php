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
        return $this->hasOne(WeatherSubscription::class, 'payment_id');
    }

    public function market_subscription()
    {
        return $this->belongsTo(MarketSubscription::class, 'payment_id');
    }

    public function insurance_subscription()
    {
        return $this->hasMany(InsuranceSubscription::class, 'payment_id');
    }

    public function ussd_weather_session()
    {
        return $this->belongsTo(UssdSessionData::class, 'weather_session_id');
    }

    public function ussd_market_session()
    {
        return $this->belongsTo(UssdSessionData::class, 'market_session_id');
    }

    public function ussd_insurance_session()
    {
        return $this->belongsTo(UssdSessionData::class, 'insurance_session_id');
    }
}
