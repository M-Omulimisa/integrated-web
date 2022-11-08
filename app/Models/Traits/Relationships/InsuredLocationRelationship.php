<?php

namespace App\Models\Traits\Relationships;

use App\Models\Insurance\InsuranceCalculatorValue;
use App\Models\Settings\Location;

/**
 * Class LocationRelationship.
 */
trait InsuredLocationRelationship
{
    public function calculator()
    {
        return $this->belongsTo(InsuranceCalculatorValue::class, 'calculator_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
