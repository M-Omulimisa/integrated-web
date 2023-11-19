<?php

namespace App\Traits;

use Log;
use Illuminate\Http\Request;

/**
 * 
 */
class DmarkSms
{
    protected $_username;
    protected $_password;
    protected $environment;
  
  public function __construct($dmark_username, $dmark_password, $dmark_environment = "production")
  {
    $this->_username  = $dmark_username;
    $this->_password  = $dmark_password;

    $this->environment  = $dmark_environment;   
  }

    public  function postXml($url) {
            $soap_do = curl_init(); 
            curl_setopt($soap_do, CURLOPT_URL, $url);   
            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 120); 
            curl_setopt($soap_do, CURLOPT_TIMEOUT,        120); 
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
            return $soap_do;
       }


       /**
       * Response
       *
       <?xml version="1.0" encoding="ISO-8859-1"?>
        <!DOCTYPE dmarkblast [<!ELEMENT dmarkblast  (error|info+|credit)><!ELEMENT error    (#PCDATA)><!ELEMENT info    (#PCDATA)><!ELEMENT credit  (#PCDATA)>]>
        <dmarkblast>
        <info>message sent [1/1]</info>
        <status>
            <item>
                <id>1731620</id>
                <msisdn>0775666852</msisdn>
            </item>
        </status>
        </dmarkblast>
        */

        /*
        * New response
        * {"Failed": 0, "Total": 1, "Detail": [{"phone": "256XXXXXXX", "msg_id": 2047158}], "Submitted": 1}
        * '{"Failed": 0, "Total": 1, "Detail": [{"phone": "0775666852", "msg_id": 8773384}], "Submitted": 1}
        * '{"error": "insufficient credits! (-49) credits remaining"}
        */

    public function send($receiver, $message_body, $sender = 8008)
    {
        $url = "https://sms.dmarkmobile.com/v2/api/send_sms/?spname=".urlencode($this->_username)."&sppass=".urlencode($this->_password)."&numbers=".urlencode($receiver)."&msg=".urlencode($message_body)."&type=json";
        //".urlencode($sender)

        $info               = "Failed";
        $id                 = null;
        $msisdn             = null;  

        $curl_request = $this->postXml($url);

        $response = curl_exec($curl_request);

        // Log::info(['Result' => $response]);

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            $data_response = curl_error($curl_request);
        }
        else { 
                libxml_use_internal_errors(true);
                $result=preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);

                try {
                    if ($result == '' || $result == null ) {
                        $data_response = '{"Failed": 1, "Submitted":"No Response"}';
                    }else{
                        $data_response = $result;
                    }
                } 
                catch (Exception $e) {
                    $data_response = '{"Failed":"Error","Submitted":"Unable to process SMS"}';  
                }
                
                $response = json_decode($data_response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code'];
            }   

            // Log::info(['SendSMSResponse' => $response, 'responseStatusCode' => $responseStatusCode]);

            curl_close($curl_request);

            if (isset($result) && !is_null($result)) {
                if (isset($response->Failed)) {
                    $info               = $response->Failed == 0 ? "Sent" : "Failed";
                    $id                 = $response->Submitted > 0 ? "Message sent" : $response->Submitted;
                }
                elseif(isset($response->error)){
                    $info               = "Error";
                    $id                 = $response->error;
                }
                
                $msisdn             = $receiver; 

                $obj=  (object) [
                        'status' => $info,
                        'id'     => $id,
                        'msisdn' => $msisdn
                    ];

                    $sms = array($obj);
                    return $sms;          
            }

            return null;
    }

    public function sendSmsToUser($message_body, $user){

        try {
            $send_sms_url = config('app.dmark_send_sms_url');
            $response = Http::get($send_sms_url, [
                'spname' => config('app.dmark_username'),
                'sppass' => config('app.dmark_password'),
                'numbers' => $user->phone_number,
                'msg' => $message_body,
                'type' => 'json'
            ]);
            info($response->json());

            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send sms");

            return false;
        }


    }

}