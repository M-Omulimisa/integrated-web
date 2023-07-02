<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;

use App\Models\Extension\ExtensionOfficerLanguage;
use App\Models\Extension\ExtensionOfficerPosition;
use App\Models\Organisations\Organisation;
use App\Models\Settings\Location;
use App\Models\Settings\Country;
use App\Models\Extension\ExtensionOfficer;

/**
 * Class LocationRelationship.
 */
trait ExtensionOfficerRelationship
{
    public function languages()
    {
        return $this->hasMany(ExtensionOfficerLanguage::class, 'extension_officer_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(ExtensionOfficerPosition::class, 'position_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(ExtensionOfficer::class, 'extension_officer_id');
    }
}
