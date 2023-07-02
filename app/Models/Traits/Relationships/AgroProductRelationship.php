<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\MeasureUnit;

/**
 * Class LocationRelationship.
 */
trait AgroProductRelationship
{

    public function unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }
}
