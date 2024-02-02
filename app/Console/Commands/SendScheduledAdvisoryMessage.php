<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ussd\UssdAdvisoryMessageOutbox;
use Illuminate\Support\Facades\Http;

class SendScheduledAdvisoryMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unified:send-scheduled-advisory-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        
        $scheduled_batches = UssdAdvisoryMessageOutbox::where('status', 'scheduled')->distinct()->get();

        foreach($scheduled_batches as $batch){

            $message = UssdAdvisoryMessageOutbox::with('session')->where('batch_number', $batch->batch_number)->where('status', 'scheduled')
            ->orderBy('message_schedule_number', 'desc')->first();


            
        try {

            $send_sms_url = config('app.dmark_send_sms_url');
            $response = Http::get($send_sms_url, [
                'spname' => config('app.dmark_username'),
                'sppass' => config('app.dmark_password'),
                'numbers' => $message->session->phone_number,
                'msg' => $message->message,
                'type' => 'json'
            ]);

            $update_message_outbox = UssdAdvisoryMessageOutbox::findorFail($message->id);
            $update_message_outbox->status = "processed";
            $update_message_outbox->save();
            
            
        } catch (\Exception $e) {
            Log::error("Failed to send sms");



            
        }



        }
    }
}
