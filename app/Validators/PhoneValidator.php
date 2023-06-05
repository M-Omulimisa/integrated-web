<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Settings\Country;

class PhoneValidator implements Rule
{
    public function passes($attribute, $value)
    {
        $country = Country::where('dialing_code','LIKE', '%'.(substr($value, 0, 3)).'%')->first();
        $country_code = $country->dialing_code;
        $length = $country->length;

    	$value = str_replace('+', '', $value);
        return strlen($value) == $length && substr($value, 0, strlen($country_code)) == $country_code;
    }

    public function message()
    {
        return 'Invalid :attribute';
    }
}