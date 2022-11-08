<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;

/**
 * Class LocationRelationship.
 */
trait LanguageRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
