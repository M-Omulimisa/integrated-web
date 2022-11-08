<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\Country;
use App\Models\Settings\Season;
use App\Models\Insurance\InsuredLocation;
use App\Models\Insurance\InsuredEnterprise;
use App\Models\Insurance\InsuredAnnualEnterprise;
use App\Models\Insurance\InsuranceCommission;

/**
 * Class LocationRelationship.
 */
trait InsuranceCalculatorRelationship
{

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function isLocationWithSubsidy($location)
    {
        $location = InsuredLocation::whereLocationId($location)->whereCalculatorId($this->id)->first();
        return $location ? true : false;
    }

    public function isEnterpriseWithSubsidy($enterprise)
    {
        $enterprise = InsuredEnterprise::whereEnterpriseId($enterprise)->whereCalculatorId($this->id)->first();
        return $enterprise ? true : false;
    }

    public function isEnterpriseAnnual($enterprise)
    {
        $enterprise = InsuredAnnualEnterprise::whereEnterpriseId($enterprise)->whereCalculatorId($this->id)->first();
        return $enterprise ? true : false;
    }

    public function locations()
    {
        return $this->hasMany(InsuredLocation::class, 'calculator_id', 'id');
    }

    public function enterprises()
    {
        return $this->hasMany(InsuredEnterprise::class, 'calculator_id', 'id');
    }

    public function commission_rankings()
    {
        return $this->hasMany(InsuranceCommission::class, 'calculator_id', 'id');
    }
}
