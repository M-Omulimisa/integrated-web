<?php

namespace App\Models\Calculator;

use App\Models\Settings\Crop;
use Illuminate\Database\Eloquent\Model;

class InsuranceCalculator extends Model
{
    protected $table = "calculator_values";
    protected $fillable = [
            
            'sum_insured',
            'sum_insured_special',

            'govt_subsidy_none',
            'govt_subsidy_small_scale',
            'govt_subsidy_large_scale',
            'govt_subsidy_districts',

            'scale_limit',
            'ira_levy',
            'vat',
            
            'individual_commission',
            'company_commission',
            'company_agent_commission',
            'self_village_agent_commission',
            'village_agent_commission',
            'micro_village_agent_commission',
            'maintenance_fee'
        ];
}

