<?php

namespace App\Models\Traits\Relationships;

use App\Models\Organisations\Organisation;
use App\Models\Settings\CountryAdminUnit;
use App\Models\Extension\ExtensionOfficerPositionLocation;

/**
 * Class LocationRelationship.
 */
trait ExtensionPositionRelationship
{
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function administration_level()
    {
        return $this->belongsTo(CountryAdminUnit::class, 'admin_level');
    }

    public function locations()
    {
        return $this->hasMany(ExtensionOfficerPositionLocation::class, 'position_id');
    }
}
