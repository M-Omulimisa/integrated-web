<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

use App\Models\Api\PersonalAccessClient;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! $this->app->routesAreCached()) {
            // Passport::routes();

            // Customization of the passport routes
            Passport::routes(null, ['prefix' => 'api/v1/oauth']);

            // php artisan route:list --path=oauth
            // route::cache
            // route::clear
            // optimize

            // use this instead of its version
            Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

            Passport::tokensExpireIn(now()->addSeconds(7201));
            Passport::refreshTokensExpireIn(now()->addSeconds(7201));
            Passport::personalAccessTokensExpireIn(now()->addSeconds(7201));
        }
    }
}
