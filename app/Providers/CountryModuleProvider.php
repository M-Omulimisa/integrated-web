<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Settings\Country;
use App\Models\Settings\SystemModule;
use Illuminate\Support\Facades\Gate;

class CountryModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            SystemModule::get()->map(function ($system_module) {
                Gate::define($system_module->name, function ($user) use ($system_module) {

                    $country =  Country::findorFail($user->country_id);
                    return $country->countryHasModule($system_module->name);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }

    }
}
