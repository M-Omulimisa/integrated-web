<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\EnterpriseVariety;

/**
 * Class LocationRelationship.
 */
trait EnterpriseTypeRelationship
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variety()
    {
        return $this->belongsTo(EnterpriseVariety::class, 'enterprise_variety_id');
    }
}
