<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\Language;
use App\Models\Settings\Location;
use App\Models\Organisations\Organisation;
use App\Models\Farmers\Farmer;
use App\Models\Weather\WeatherSubscription;

/**
 * Class LocationRelationship.
 */
trait WeatherSubscriptionRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function renew()
    {
        return $this->belongsTo(WeatherSubscription::class, 'renewal_id');
    }
}
