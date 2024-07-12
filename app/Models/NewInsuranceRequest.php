<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewInsuranceRequest extends Model
{
    protected $fillable = [
        'session_id', 'phone_number', 'insurance_subscrption_for', 'insurance_enterprise_id',
        'insurance_amount', 'insurance_subscriber', 'insurance_acreage',
        'insurance_sum_insured', 'insurance_premium', 'markup', 'insurance_coverage',
        'confirmation_message', 'insurance_region_id', 'agent_id', 'insurer_name',
        'insurance_type', 'surname', 'telephone', 'other_name', 'payment_phone',
        'paid', 'completed', 'pending', 'cancelled', 'national_id', 'village_id',
        'driving_license', 'passport', 'email', 'lat', 'long', 'category',
        'agent_sale', 'environments', 'animal_production_business_duration',
        'profession', 'animals_in_posession_duration', 'animals_keeping_purpose',
        'loan', 'selected_animals', 'animals_lost', 'selected_products',
        'causes_of_death', 'animal_health', 'animal_illness', 'animal_treatment',
        'animal_contagious', 'risks', 'conviction', 'additional_info',
        'management', 'supervisory', 'security', 'laborer',
        'sub_county', 'parish', 'village', 'district',
        "payment_id", "method", "approved"
    ];

    protected $table = "new_insurance_requests";
}
