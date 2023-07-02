<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Location;
use App\Models\Settings\CountryAdminUnit;
use App\Models\Settings\Country;
/**
 * Class LocationRelationship.
 */
trait LocationRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id', 'id');
    }

    public function get_admin_unit()
    {
        $parent_id = $this->parent_id;
        $counter = 1;

        if (!is_null($parent_id)) {
            $counter = 2;
            $admin_units = CountryAdminUnit::whereCountryId($this->country_id)->get();
            foreach ($admin_units as $unit) {
                $location = Location::whereId($parent_id)->first();
                if ($location) {
                    $parent_id = $location->parent_id;
                    if (!is_null($parent_id)) $counter++;
                }
            }
        }
        return $counter;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
