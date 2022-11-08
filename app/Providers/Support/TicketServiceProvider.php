<?php

namespace App\Providers\Support;

use Illuminate\Support\ServiceProvider;
use App\Services\Support\TicketService;

class TicketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ticket-service', function ($app) {
            return $app->make(TicketService::class);
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
