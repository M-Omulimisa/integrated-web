<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;
use App\Models\Settings\Season;
use App\Models\Settings\Location;

/**
 * Class LocationRelationship.
 */
trait InsuranceLossRelationship
{

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
