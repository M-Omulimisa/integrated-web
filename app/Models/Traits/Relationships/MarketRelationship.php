<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\Location;

/**
 * Class LocationRelationship.
 */
trait MarketRelationship
{
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
