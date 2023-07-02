<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\AgentCommissionRanking;

/**
 * Class LocationRelationship.
 */
trait InputCommissionRateRelationship
{

    public function ranking()
    {
        return $this->belongsTo(AgentCommissionRanking::class, 'commission_ranking_id');
    }
}
