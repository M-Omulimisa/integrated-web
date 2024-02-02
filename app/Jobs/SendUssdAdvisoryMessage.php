<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Ussd\UssdSession;
use App\Models\Ussd\UssdAdvisoryMessageOutbox;
use App\Models\Ussd\UssdQuestionOption;
use App\Models\Ussd\UssdAdvisoryQuestion;
use App\Models\Ussd\UssdAdvisoryMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class SendUssdAdvisoryMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $session_id;

    protected $position;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($session_id, $posiion)
    {
        $this->session_id = $session_id;

        $this->position = $posiion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $session =  UssdSession::where('session_id', $this->session_id)->first();

        $question = UssdAdvisoryQuestion::where('ussd_advisory_topic_id', $session->data['topic_id'])->first();

        $question_option_selected = UssdQuestionOption::where('ussd_advisory_question_id', $question->id)->where('position',$this->position)->first();
  
      

        $messages_to_send = UssdAdvisoryMessage::where('ussd_question_option_id', $question_option_selected->id)->get();

        $batch_number = Str::uuid();

        $outbox_uuid = Str::uuid();

       
        $counter = 1;
        foreach($messages_to_send as $message){

            $save_message_to_outbox =  new UssdAdvisoryMessageOutbox();
            $save_message_to_outbox->message = $message->message;
            $save_message_to_outbox->session_id = $session->id;
            $save_message_to_outbox->batch_number = $batch_number;
            $save_message_to_outbox->message_schedule_number = $counter;
            $save_message_to_outbox->status = 'scheduled';
            $save_message_to_outbox->save();

            $counter++;
        }

        $initial_message_to_send = UssdAdvisoryMessageOutbox::where('batch_number', $batch_number)->where('session_id', $session->id)
        ->where('message_schedule_number', 1)
        ->first();

        try {

            $send_sms_url = config('app.dmark_send_sms_url');
            $response = Http::get($send_sms_url, [
                'spname' => config('app.dmark_username'),
                'sppass' => config('app.dmark_password'),
                'numbers' => $session->phone_number,
                'msg' => $initial_message_to_send->message,
                'type' => 'json'
            ]);

            $update_message_outbox = UssdAdvisoryMessageOutbox::findorFail($initial_message_to_send->id);
            $update_message_outbox->status = "processed";
            $update_message_outbox->save();
            
            
        } catch (\Exception $e) {
            Log::error("Failed to send sms");



            
        }

            
        
    }
}
