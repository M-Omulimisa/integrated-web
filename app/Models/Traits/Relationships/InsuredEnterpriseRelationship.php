<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Enterprise;
use App\Models\Insurance\InsuranceCalculatorValue;

/**
 * Class LocationRelationship.
 */
trait InsuredEnterpriseRelationship
{
    public function calculator()
    {
        return $this->belongsTo(InsuranceCalculatorValue::class, 'calculator_id');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }
}
