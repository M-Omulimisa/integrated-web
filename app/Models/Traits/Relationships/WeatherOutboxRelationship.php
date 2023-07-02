<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;

use App\Models\Weather\WeatherSubscription;
use App\Models\Farmers\Farmer;

/**
 * Class LocationRelationship.
 */
trait WeatherOutboxRelationship
{
    public function subscription()
    {
        return $this->belongsTo(WeatherSubscription::class, 'subscription_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
}
