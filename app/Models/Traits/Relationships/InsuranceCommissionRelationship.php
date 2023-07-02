<?php

namespace App\Models\Traits\Relationships;

use App\Models\Insurance\InsuranceCalculatorValue;
use App\Models\Settings\Location;
use App\Models\Settings\AgentCommissionRanking;

/**
 * Class LocationRelationship.
 */
trait InsuranceCommissionRelationship
{
    public function calculator()
    {
        return $this->belongsTo(InsuranceCalculatorValue::class, 'calculator_id');
    }

    public function ranking()
    {
        return $this->belongsTo(AgentCommissionRanking::class, 'commission_ranking_id');
    }
}
