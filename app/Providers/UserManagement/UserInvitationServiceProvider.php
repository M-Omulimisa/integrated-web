<?php

namespace App\Providers\UserManagement;

use Illuminate\Support\ServiceProvider;
use App\Services\UserManagement\UserInvitationService;

class UserInvitationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('user-invitation-service', function ($app) {
            return $app->make(UserInvitationService::class);
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
