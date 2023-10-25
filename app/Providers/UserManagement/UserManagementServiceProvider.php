<?php

namespace App\Providers\UserManagement;

use Illuminate\Support\ServiceProvider;
use App\Services\UserManagement\UserManagementService;

class UserManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('user-management-service', function ($app) {
            return $app->make(UserManagementService::class);
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
