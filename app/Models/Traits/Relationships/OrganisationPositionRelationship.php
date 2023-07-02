<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Organisations\Organisation;
use App\Models\Organisations\OrganisationPositionPermission;

/**
 * Class LocationRelationship.
 */
trait OrganisationPositionRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }

    public function permissions()
    {
        return OrganisationPositionPermission::where('position_id', $this->id)->get();
    }
}
