<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Buyer;
use App\Models\Settings\AgroProduct;
use App\Models\Loans\Distributor;

/**
 * Class LocationRelationship.
 */
trait DistributorAgroProductRelationship
{
    public function agro_product()
    {
        return $this->belongsTo(AgroProduct::class, 'agro_product_id', 'id');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id', 'id');
    }
}
