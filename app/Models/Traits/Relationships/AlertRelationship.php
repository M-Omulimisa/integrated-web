<?php

namespace App\Models\Traits\Relationships;

use App\Models\Alerts\AlertRecipient;
use App\Models\Alerts\AlertEnterprise;
use App\Models\Alerts\AlertLocation;
use App\Models\Alerts\AlertLanguage;

/**
 * Class LocationRelationship.
 */
trait AlertRelationship
{
    
    public function recipients()
    {
        return $this->hasMany(AlertRecipient::class, 'alert_id');
    }

    public function locations()
    {
        return $this->hasMany(AlertLocation::class, 'alert_id');
    }

    public function enterprises()
    {
        return $this->hasMany(AlertEnterprise::class, 'alert_id');
    }

    public function languages()
    {
        return $this->hasMany(AlertLanguage::class, 'alert_id');
    }
}
