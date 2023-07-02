<?php

namespace App\Validators;

use Illuminate\Contracts\Validation\Rule;

class FullnameValidator implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $pattern = '/^[A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+([\ A-Za-z\x{00C0}-\x{00FF}][A-Za-z\x{00C0}-\x{00FF}\'\-]+)*/u';
        // $pattern = '/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/';
        // if (preg_match($pattern, $value) && strlen($value) > 2)
        if(preg_match($pattern, $value)){
            return true;
        }else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A name must only contain letters.';
    }
}