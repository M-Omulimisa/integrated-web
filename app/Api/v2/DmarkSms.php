<?php

namespace App\Api\v2;

use Log;
use Illuminate\Http\Request;
use App\Api\v1\MtnPay;

/**
 * 
 */
class DmarkSms
{
    protected $_username;
    protected $_password;

    public function __construct(MtnPay $mtnpay)
    {
        $this->_username  = config('dmark.username');
        $this->_password  = config('dmark.password');
        $this->mtnpay     = $mtnpay;
    }

    public  function postJSON($url) {
            $soap_do = curl_init(); 
            curl_setopt($soap_do, CURLOPT_URL, $url);   
            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 120); 
            curl_setopt($soap_do, CURLOPT_TIMEOUT,        120); 
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
            return $soap_do;
       }

       /**
        * V2
        * Request
        * ------------------------------
        * https://sms.dmarkmobile.com/v2/api/send_sms/?spname=username&sppass=password&numbers=256XXXXXXX&msg=message&type=json
        Parameters for sending via API
            username : mulimisa
            password : mul1m1s4
            numbers:  Receiver phone number
            msg: Message to be sent (urlencoded)
            type: xml|json - Optional(defaults to json)

        Response
        ------------------------------
        {"Failed": 0, "Total": 1, "Detail": [{"phone": "256XXXXXXX", "msg_id": 2047158}], "Submitted": 1}
        **/ 

        /**
         * Delivery status endpoint (Optional)
         * =================================
         * Request
         * ------------------------------
         * https://sms.dmarkmobile.com/v2/api/dlr/?msg_id=2047158
        Parameters sent back to dlr-url
            msg_id: request_id - (id (bigint) of the message to customer sent via the api)
            submitted_at: 2019-07-18 00:00:00 - (Delivery report receipt status time)
            status: delivered - (Delivery report status)

        Response
        ------------------------------
        {"status": "DELIVERED", "submitted_at": "2019-11-18 13:06:14.384962", "msg_id": 2047158}
        **/

    public function sendMessage($receiver, $message_body, $sender = 8228)
    {
        $url = "https://sms.dmarkmobile.com/v2/api/send_sms/?spname=".urlencode($this->_username)."&sppass=".urlencode($this->_password)."&numbers=".urlencode($receiver)."&msg=".urlencode($message_body)."&type=json";

        $info               = "Failed";
        $id                 = null;
        $msisdn             = null; 

        if ( strlen($receiver) == 0 || strlen($message_body) == 0 ) {
          return null;
        } 

        $curl_request = $this->postJSON($url);

        $response = curl_exec($curl_request);

        Log::info(['Result' => $response]);

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            Log::info(['ResponseError' => $response, 'FailedResponseMsg' => curl_error($curl_request)]);
        }
        else {
                $response = json_decode($response, true);

                Log::info(['Response' => $response]);

                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200) {
                    $result = $response;
                }
                else{
                    //errors code here
                }
                Log::info(['SendSMSResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }    

            curl_close($curl_request);

            if (isset($result) && !is_null($result)) {
                $info               = (string) $result['Failed'] == "0" ? "Sent" : "Failed";
                $datail             =  $result['Detail'];
                $id                 = (string) $datail[0]['msg_id'];
                $msisdn             = (string) $datail[0]['phone']; 

                /*
                Output
                array:1 [▼
                  0 => {#622 ▼
                    +"property": "Here we go"
                  }
                ]
                */

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
}