<?php

namespace App\Models\Traits\Relationships;


use App\Models\Loans\Microfinance;
/**
 * Class LocationRelationship.
 */
trait LoanChargeRelationship
{

    public function microfinance()
    {
        return $this->belongsTo(Microfinance::class, 'microfinance_id');
    }
}
