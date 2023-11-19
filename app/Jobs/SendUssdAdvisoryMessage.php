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
   
       

        foreach($messages_to_send as $message){

            $save_message_to_outbox =  UssdAdvisoryMessageOutbox::create([

                'message' => $message->message,
                'session_id' => $session->id
            ]);

            try {

                $send_sms_url = config('app.dmark_send_sms_url');
                $response = Http::get($send_sms_url, [
                    'spname' => config('app.dmark_username'),
                    'sppass' => config('app.dmark_password'),
                    'numbers' => $session->phone_number,
                    'msg' => $message->message,
                    'type' => 'json'
                ]);

                $update_message_outbox = UssdAdvisoryMessageOutbox::findorFail($save_message_to_outbox->id);
                $update_message_outbox->status = "processed";
                $update_message_outbox->save();
                
                
            } catch (\Exception $e) {
                Log::error("Failed to send sms");
    
  

                
            }

            
        }
    }
}