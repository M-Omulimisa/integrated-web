<?php

namespace App\Models\Traits\Relationships;

use App\Models\User;
use App\Models\Settings\MeasureUnit;
use App\Models\Settings\Enterprise;

/**
 * Class LocationRelationship.
 */
trait MarketCommodityRelationship
{
    public function unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
