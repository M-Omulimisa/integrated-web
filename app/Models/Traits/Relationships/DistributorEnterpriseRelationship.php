<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Buyer;
use App\Models\Settings\Enterprise;
use App\Models\Loans\Distributor;

/**
 * Class LocationRelationship.
 */
trait DistributorEnterpriseRelationship
{
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id', 'id');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }
}
