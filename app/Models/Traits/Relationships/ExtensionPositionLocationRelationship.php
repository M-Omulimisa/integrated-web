<?php

namespace App\Models\Traits\Relationships;

use App\Models\Extension\ExtensionOfficerPosition;
use App\Models\Settings\Location;

/**
 * Class LocationRelationship.
 */
trait ExtensionPositionLocationRelationship
{
    public function position()
    {
        return $this->belongsTo(ExtensionOfficerPosition::class, 'position_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
