<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Enterprise;
use App\Models\Settings\Country;
use App\Models\Settings\Season;

/**
 * Class LocationRelationship.
 */
trait InsurancePremiunRelationship
{

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
