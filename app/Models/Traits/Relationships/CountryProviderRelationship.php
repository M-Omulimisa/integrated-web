<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;

/**
 * Class CountryProvider.
 */
trait CountryProviderRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
