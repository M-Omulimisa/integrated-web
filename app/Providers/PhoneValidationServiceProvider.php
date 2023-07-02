<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\IdValidations\PhoneValidationService;

class PhoneValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('phone-validation-service', function ($app) {
            return $app->make(PhoneValidationService::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
