<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Users\Role;

use App\Models\Settings\Location;
use App\Models\Settings\Country;
use App\Models\Organisations\Organisation;
use App\Models\Farmers\FarmerGroup;
use App\Models\Agents\Agent;

/**
 * Class LocationRelationship.
 */
trait AgentRelationship
{
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Agent::class, 'village_agent_id');
    }
}
