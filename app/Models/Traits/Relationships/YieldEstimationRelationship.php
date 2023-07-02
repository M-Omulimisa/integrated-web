<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\MeasureUnit;
use App\Models\Settings\EnterpriseVariety;

/**
 * Class LocationRelationship.
 */
trait YieldEstimationRelationship
{
    public function farm_unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'farm_size_unit_id');
    }

    public function input_unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'input_unit_id');
    }

    public function output_unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'output_unit_id');
    }

    public function variety()
    {
        return $this->belongsTo(EnterpriseVariety::class, 'enterprise_variety_id');
    }
}
