<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\CountryModule;
use App\Models\Loans\LoanInputCommissionEnterprise;
use App\Models\Loans\LoanInputCommissionRate;

/**
 * Class LocationRelationship.
 */
trait CountryRelationship
{
    public function modules()
    {
        return $this->hasMany(CountryModule::class, 'country_id', 'id');
    }

    public function input_loan_enterprises()
    {
        return $this->hasMany(LoanInputCommissionEnterprise::class, 'country_id', 'id');
    }

    public function input_loan_commission_rates()
    {
        return $this->hasMany(LoanInputCommissionRate::class, 'country_id', 'id');
    }
}
