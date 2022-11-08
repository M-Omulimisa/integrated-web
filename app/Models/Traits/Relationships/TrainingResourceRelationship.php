<?php

namespace App\Models\Traits\Relationships;

use App\Models\Training\TrainingResourceEnterprise;
use App\Models\Training\TrainingResourceLanguage;
use App\Models\Training\TrainingResourceSection;

/**
 * Class LocationRelationship.
 */
trait TrainingResourceRelationship
{
    public function enterprises()
    {
        return $this->hasMany(TrainingResourceEnterprise::class, 'resource_id');
    }

    public function languages()
    {
        return $this->hasMany(TrainingResourceLanguage::class, 'resource_id');
    }

    public function sections()
    {
        return $this->hasMany(TrainingResourceSection::class, 'resource_id');
    }
}
