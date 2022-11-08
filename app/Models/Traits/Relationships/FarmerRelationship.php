<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;

use App\Models\Settings\Language;
use App\Models\Settings\Location;
use App\Models\Settings\Country;
use App\Models\Organisations\Organisation;
use App\Models\Farmers\FarmerGroup;
use App\Models\Farmers\FarmerEnterprise;
use App\Models\Agents\Agent;

/**
 * Class LocationRelationship.
 */
trait FarmerRelationship
{
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function farmer_group()
    {
        return $this->belongsTo(FarmerGroup::class, 'farmer_group_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function enterprises()
    {
        return $this->hasMany(FarmerEnterprise::class, 'farmer_id', 'id');
    }

    public function added_by_user()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function added_by_agent()
    {
        return $this->belongsTo(Agent::class, 'created_by_agent_id');
    }

    public function managed_by()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
