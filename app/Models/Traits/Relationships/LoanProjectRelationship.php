<?php

namespace App\Models\Traits\Relationships;

use App\Models\Organisations\Organisation;

/**
 * Class LocationRelationship.
 */
trait LoanProjectRelationship
{

    public function organisation()
    {
        return $this->belongsTo(Organisation::class, 'organisation_id');
    }
}
