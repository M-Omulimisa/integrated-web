<?php

namespace App\Providers\Support;

use Illuminate\Support\ServiceProvider;
use App\Services\Support\TicketCommentService;

class TicketCommentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ticket-comment-service', function ($app) {
            return $app->make(TicketCommentService::class);
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
