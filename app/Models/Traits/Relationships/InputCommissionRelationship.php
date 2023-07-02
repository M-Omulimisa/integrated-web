<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\CountryModule;
use App\Models\Loans\LoanInputCommissionRate;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\EnterpriseType;

/**
 * Class LocationRelationship.
 */
trait InputCommissionRelationship
{

    public function commissions()
    {
        return $this->hasMany(LoanInputCommissionRate::class, 'loan_input_commission_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }

    public function variety()
    {
        return $this->belongsTo(EnterpriseVariety::class, 'enterprise_variety_id');
    }

    public function type()
    {
        return $this->belongsTo(EnterpriseType::class, 'enterprise_type_id');
    }
}
