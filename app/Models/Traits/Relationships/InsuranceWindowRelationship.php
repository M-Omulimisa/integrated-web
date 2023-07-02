<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Season;

/**
 * Class LocationRelationship.
 */
trait InsuranceWindowRelationship
{

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
