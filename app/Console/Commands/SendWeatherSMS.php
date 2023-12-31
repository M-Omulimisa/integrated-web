<?php

namespace App\Console\Commands;

use Log;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Weather\WeatherOutbox;
use App\Models\Weather\WeatherSubscription;
use App\Services\OtpServices\ServiceFactory;

class SendWeatherSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:send-weather-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send already composed weather info messages';

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
     * @return mixed
     */
    public function handle()
    {
        if ($this->debug) Log::info(['Command' => 'Sending weather information']);

        try {
            WeatherOutbox::where('status', 'PENDING')
                ->whereDate('created_at', Carbon::today())
                ->orWhere('status', 'PENDING')
                ->whereDate('updated_at', Carbon::today())
                ->orWhere('status', 'PROCESSING')
                ->whereNotNull('processsed_at')
                ->whereRaw('TIMESTAMPDIFF(MINUTE,processsed_at,"'.Carbon::now()->format('Y-m-d H:i:s').'") >= 60')
                ->chunk(500, function ($messages) {

                    if ($this->debug) logger(count($messages));
                    if ($this->debug) echo count($messages);                        
                    if ($this->debug) logger([$messages->pluck('id')->toArray()]);

                    WeatherOutbox::whereIn('id', $messages->pluck('id')->toArray())->update(['status' => 'SELECTED']);

                    foreach ($messages as $message) {

                        $message->update(['status' => 'PROCESSING', 'processsed_at' => Carbon::now()]);

                        $failure = '';
                        $result = false;

                        $SMSFactory = new ServiceFactory();
                        $service = $SMSFactory->getService(config("otp.otp_default_service", null));

                        if ($service) {
                            $result = $service->sendTextMessage($message->recipient, $message->message);
                        }

                        if ($this->debug) Log::info($result);

                        if (!$result) $failure .= 'Failed'; 
                        if ($result) {
                             $message->update([
                                'sent_at'     => Carbon::now()
                            ]);
                        }

                        if ($failure != '') {
                            $message->update([
                                'failure_reason'=> $failure,
                                'failed_at'     => Carbon::now()
                            ]);
                        }

                        $message->update([
                            'status' => $result ? 'SUCCESSFUL' : 'FAILED', 
                            'statuses' => $result ? 'Sent' : 'Failed'
                        ]);

                        WeatherSubscription::where('id', $message->subscription_id)->update([
                            'outbox_generation_status'  => false,
                            'outbox_reset_status'       => true,
                            'outbox_last_date'          => date('Y-m-d')
                        ]);                        
                    }
                });

        }
        catch (\Throwable $r) {
            Log::error(['SendWeatherSMS' => $r->getMessage()]);            
        } 
    }
}
