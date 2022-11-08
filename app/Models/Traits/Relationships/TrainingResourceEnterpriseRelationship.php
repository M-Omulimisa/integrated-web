<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Enterprise;

/**
 * Class LocationRelationship.
 */
trait TrainingResourceEnterpriseRelationship
{
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id', 'id');
    }
}
