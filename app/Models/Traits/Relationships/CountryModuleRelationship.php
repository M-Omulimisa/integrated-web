<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;
use App\Models\Settings\SystemModule;

/**
 * Class LocationRelationship.
 */
trait CountryModuleRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function module()
    {
        return $this->belongsTo(SystemModule::class, 'module_id', 'id');
    }

    public function country_obj()
    {
        return Country::whereId($this->country_id)->first();
    }

    public function modules()
    {
        return $this->where('country_id', $this->id)->get();
    }
}
