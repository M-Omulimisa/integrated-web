<?php

namespace App\Models\Traits\Relationships;

use App\Models\Settings\CountryModule;
use App\Models\Loans\LoanInputCommissionRate;
use App\Models\Settings\Enterprise;
use App\Models\Settings\EnterpriseVariety;
use App\Models\Settings\EnterpriseType;
use App\Models\Settings\MeasureUnit;
use App\Models\Settings\Currency;
use App\Models\Settings\Season;
use App\Models\Loans\Buyer;

/**
 * Class LocationRelationship.
 */
trait OutputPriceRelationship
{

    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'buyer_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
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

    public function unit()
    {
        return $this->belongsTo(MeasureUnit::class, 'unit_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
