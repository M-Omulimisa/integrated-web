<?php

namespace App\Models\Traits\Relationships;

use App\Models\Farmer\FarmerGroup;
use App\Models\Settings\Enterprise;

/**
 * Class LocationRelationship.
 */
trait FarmerGroupEnterpriseRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function farmer_group()
    {
        return $this->belongsTo(FarmerGroup::class, 'farmer_group_id', 'id');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id', 'id');
    }
}
