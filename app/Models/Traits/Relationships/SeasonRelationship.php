<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;

/**
 * Class LocationRelationship.
 */
trait SeasonRelationship
{

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
