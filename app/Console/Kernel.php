<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        Commands\ProcessMarketSubscriptionPayment::class,
        Commands\ProcessWeatherSubscriptionPayment::class,
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('unified:process-market-subscription-payment')->everyMinute();
        $schedule->command('unified:process-weather-subscription-payment')->everyMinute();
        $schedule->command('unified:process-insurance-subscription-payment')->everyMinute();
        $schedule->command('unified:generate-weather-sms-outbox '.env('WTHR_START').' '.env('WTHR_START_A').' '.env('WTHR_END').' '.env('WTHR_END_B'))->everyMinute();
        $schedule->command('unified:send-weather-sms')->everyMinute();
        $schedule->command('unified:reset-failed-weather-sms-outbox '.env('WTHR_START').' '.env('WTHR_START_A').' '.env('WTHR_END_2').' '.env('WTHR_END_B_2'))->everyMinute();
        $schedule->command('unified:send-scheduled-advisory-message')->twiceDaily(8, 17);
        $schedule->command('unified:generate-market-sms-outbox')->everyMinute();
        $schedule->command('unified:send-market-sms')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
