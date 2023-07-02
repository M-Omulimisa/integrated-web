<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Buyer;
use App\Models\Settings\Enterprise;

/**
 * Class LocationRelationship.
 */
trait BuyerEnterpriseRelationship
{
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id', 'id');
    }
}
