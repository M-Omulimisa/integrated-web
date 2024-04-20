<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Log;
use Carbon\Carbon;
use App\Models\Weather\WeatherSubscription;

class ResetFailedWeatherSmsOutbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:reset-failed-weather-sms-outbox {start_time} {start_meridiem} {end_time} {end_meridiem}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a failed weather information sms';

    /**
     * Enables debug logging
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start_time     = $this->argument('start_time');
        $start_meridiem = $this->argument('start_meridiem');
        $end_time       = $this->argument('end_time');
        $end_meridiem   = $this->argument('end_meridiem');

        if ($this->debug) Log::info(['Command' => 'Reseting weather info']);

        if (time() > strtotime($start_time.' '.$start_meridiem) && time() < strtotime($end_time.' '.$end_meridiem)) {

            if ($this->debug) Log::info(['Command' => 'Past 12am']);

            try {

                // Selected for more that one hour
                // Processing for more that one hour
                $failed_sms = WeatherSubscription::where('end_date', '>', Carbon::now())->where('outbox_generation_status', 2)->whereRaw('TIMESTAMPDIFF(HOUR, updated_at, NOW()) > ?', [1])->orWhere('end_date', '>', Carbon::now())->where('outbox_generation_status', 3)->whereRaw('TIMESTAMPDIFF(HOUR, updated_at, NOW()) > ?', [1])->limit(100)->get();

                if (count($failed_sms) > 0) {
                    
                    foreach ($failed_sms as $subscription) {
                        
                        if (is_null($subscription->outbox_last_date)) {
                            $subscription->update(['outbox_generation_status' => false, 'outbox_reset_status' => false]);
                        }
                        else {
                            $subscription->update(['outbox_generation_status' => false, 'outbox_reset_status' => true]);
                        }
                    }
                }
            }
            catch (\Throwable $r) {
                Log::error(['ResetWeatherSmsOutbox' => $r->getMessage()]);            
            } 
        }  
    }
}
