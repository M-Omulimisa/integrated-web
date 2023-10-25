<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseType;

/**
 * Class LocationRelationship.
 */
trait EnterpriseVarietyRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function types()
    {
        return $this->hasMany(EnterpriseType::class, 'enterprise_variety_id');
    }
}
