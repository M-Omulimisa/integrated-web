<?php

namespace App\Models\Settings;

use App\Models\BaseModel;
use App\Models\Traits\Settings\SettingBoot;
use App\Models\Traits\Rules\Settings\SettingRules;
use App\Models\Traits\DescriptionGeneratorTrait;
// use App\Models\Traits\Relationships\Settings\SettingRelationship;

class Setting extends BaseModel
{
    // use SettingRelationship, SettingRules, SettingBoot, DescriptionGeneratorTrait;

    protected $fillable = [
        'name', 'value', 'context', 'autoload', 'public', 'settingable_type', 'settingable_id'
    ];

    protected static $logAttributes = [
        'name', 'context'
    ];

    const EDUCATION = [
        'None' => 'None',
        'Primary' => 'Primary',
        'Secondary' => 'Secondary',
        'Tertiary' => 'Tertiary'
    ];

    const MEETING_DAYS = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];

    const MEETING_FREQUENCY = [
        'Daily',
        'Weekly',
        'Monthly',
        'Yearly'
    ];

    const KEYWORD_CATEGORY = [
        'Registration' => 'Registration',
        'Questions' => 'Questions',
        'Market Pricing' => 'Market Pricing',
    ];

    const COMPUTATION_TYPE = [
        'total' => 'total',
        'percentage' => 'percentage',
        'interest' => 'interest',
    ];    
    
    const EXT_CATEGORIES = [
        'Extension Officer' => 'Extension Officer',
        'Expert' => 'Expert',
    ];

    const MEASUREMENT_UNITS = [
        'Kilogram' => 'Kilogram',
        'Acre' => 'Acre',
        'Each' => 'Each',
        'Litre' => 'Litre',
        'Packet' => 'Packet'
    ];

    const QUESTION_FAILURE_REASONS = [
        'UNREGISTERED' => 'UNREGISTERED',
        'MISSING NAME' => 'MISSING NAME',
        
        'MISSING KEYWORD' => 'MISSING KEYWORD',
        'VAGUE MESSAGE' => 'VAGUE MESSAGE',
        'MESSAGE ERROR' => 'MESSAGE ERROR',
        'DUPLICATE MOBILE' => 'DUPLICATE MOBILE',
        'MOBILE EXITS' => 'MOBILE EXITS',
        'ONLY KEYWORD' => 'ONLY KEYWORD',
        'MULTIPLE MOBILE' => 'MULTIPLE MOBILE',
        'MULTIPLE KEYWORD' => 'MULTIPLE KEYWORD'
    ];

    const PRODUCT_TYPES = [
        'Fertilizer' => 'Fertilizer',
        'Post Harvest Material' => 'Post Harvest Material',
    ];  

    const ALETS_TIME = [
        '7AM - 8AM' => '7AM - 8AM',
        '8AM - 9AM' => '8AM - 9AM',
        '9AM - 10AM' => '9AM - 10AM',
        '10AM - 11AM' => '10AM - 11AM',
        '11AM - 12PM' => '11AM - 12PM',
        '12PM - 1PM' => '12PM - 1PM',
        '1PM - 2PM' => '1PM - 2PM',
        '2PM - 3PM' => '2PM - 3PM',
        '3PM - 4PM' => '3PM - 4PM',
        '4PM - 5PM' => '4PM - 5PM',
    ];


    public function matchedService()
    {
        $matched = array_filter(array_keys(config('settings.supported_mail_services')), function ($mail) {
            return preg_match('/'.$mail.'/', $this->attributes['context']);
        });

        return end($matched);
    }
}
