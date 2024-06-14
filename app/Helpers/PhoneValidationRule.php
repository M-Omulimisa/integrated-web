<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Rule;

class PhoneValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        return strlen($value) == 12 && substr($value, 0, 3) == "256";
    }

    public function message()
    {
        return ':attribute should be 12 digits and start with 256!';
    }
}