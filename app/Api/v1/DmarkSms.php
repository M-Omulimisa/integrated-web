<?php

namespace App\Api\v1;

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

    public function sendMessage($receiver, $message_body, $sender = 8228)
    {
        $url = "https://api.dmarkmobile.com/v1/blasta.php?spname=".urlencode($this->_username)."&sppass=".urlencode($this->_password)."&type=xml&msg=".urlencode($message_body)."&numbers=".urlencode($receiver)."&from=".urlencode($sender);

        $info               = "Failed";
        $id                 = null;
        $msisdn             = null;  

        $curl_request = $this->postXml($url);

        $response = curl_exec($curl_request);

        Log::info(['Result' => $response]);

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            Log::info(['ResponseError' => $response, 'FailedResponseMsg' => curl_error($curl_request)]);
        }
        else {  

                if (stripos($response, 'HTTP') !== false && stripos($response, 'xml') !== false) {
                    $response = strstr($response, '<?xml');
                }

                if (stripos($response, '<!DOCTYPE dmarkblast') !== false && stripos($response, 'xml') !== false) {
                    $response = str_replace("<!DOCTYPE dmarkblast [<!ELEMENT dmarkblast (error|info+|credit)><!ELEMENT error    (#PCDATA)><!ELEMENT info    (#PCDATA)><!ELEMENT credit  (#PCDATA)>]>", "", $response);
                }

                Log::info(['Response' => $response]);

                $validated_response = $this->mtnpay->isValidXML($response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200 && $validated_response) {
                    $content = $this->mtnpay->isBlankResponse($response);
                    $result = $content >= 0 ? $response : null ;
                }
                else{
                    //errors code here
                }
                Log::info(['SendSMSResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }    

            curl_close($curl_request);

            if (isset($result) && !is_null($result)) {
                $processedXML       = $this->mtnpay->processXML($result);
                $info               = (string) $processedXML->info == "message sent [1/1]" ? "Sent" : "Failed";
                $id                 = (string) $processedXML->status->item->id;
                $msisdn             = (string) $processedXML->status->item->msisdn; 

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