<?php

namespace App\Models\Traits\Relationships;

use App\Models\Farmer\Farmer;
use App\Models\Settings\Enterprise;

/**
 * Class LocationRelationship.
 */
trait FarmerEnterpriseRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id', 'id');
    }
}
