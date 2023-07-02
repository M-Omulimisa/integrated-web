<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Organisations\OrganisationPosition;

/**
 * Class LocationRelationship.
 */
trait OrganisationUserPositionRelationship
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function position()
    {
        return $this->belongsTo(OrganisationPosition::class, 'position_id');
    }
}
