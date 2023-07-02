<?php

namespace App\Facades\IdValidation;

use Illuminate\Support\Facades\Facade;

/**
 * @method static 
 *
 * @see \App\Services\Support\DisputeService
 */
class PhoneValidationServiceFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'phone-validation-service';
    }
}