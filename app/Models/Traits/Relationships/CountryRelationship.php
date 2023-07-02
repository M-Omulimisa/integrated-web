<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\CountryModule;
use App\Models\Loans\LoanInputCommissionEnterprise;
use App\Models\Loans\LoanInputCommissionRate;
use App\Models\Settings\SystemModule;

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

    public function systemModules() {

        return $this->belongsToMany(SystemModule::class,'country_modules', 'country_id', 'module_id');
    
    }

    public function countryHasModule($module){

        return (bool) $this->systemModules->where('name', $module)->count();
    }
}
