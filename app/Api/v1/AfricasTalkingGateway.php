<?php

namespace App\Api\v1;

use Exception;

class AfricasTalkingGatewayException extends Exception  {}

class AfricasTalkingGateway
{
  protected $api_username;
  protected $api_key;
  
  protected $request_body;
  protected $request_url;
  
  protected $response_body;
  protected $response_info;

  protected $environment;
    
  //Turn this on if you run into problems. It will print the raw HTTP response from our server
  const Debug             = false;
  
  const HTTP_CODE_OK      = 200;
  const HTTP_CODE_CREATED = 201;
  
  public function __construct($ait_username, $ait_api_key, $ait_environment = "production")
  {
    $this->api_username     = $ait_username;
    $this->api_key       = $ait_api_key;

    $this->environment  = $ait_environment;
    
    $this->request_body  = null;
    $this->request_url   = null;
    
    $this->response_body = null;
    $this->response_info = null;    
  }
  
  
  //Messaging methods
  public function sendMessage($recipient, $message_body, $sender = null, $bulk_sms_mode = 1, Array $sms_options = array())
  {
    if ( strlen($recipient) == 0 || strlen($message_body) == 0 ) {
      return null;
      // throw new AfricasTalkingGatewayException('Please supply both recipient and message parameters');
    }
    
    $params = array(
            'username' => $this->api_username,
            'to'       => $recipient,
            'message'  => $message_body,
            );
    
    if ( $sender !== null ) {
      $params['from']        = $sender;
      $params['bulkSMSMode'] = $bulk_sms_mode;
    }
    
    //This contains a list of parameters that can be passed in $sms_options parameter
    if ( count($sms_options) > 0 ) {
      $allowedKeys = array (
                'enqueue',
                'keyword',
                'linkId',
                'retryDurationInHours'
                );
                
      //Check whether data has been passed in options_ parameter
      foreach ( $sms_options as $key => $value ) {
    if ( in_array($key, $allowedKeys) && strlen($value) > 0 ) {
      $params[$key] = $value;
    } else {
      // throw new AfricasTalkingGatewayException("Invalid key in options array: [$key]");
    }
      }
    }
    
    $this->request_url  = $this->getSendSmsUrl();
    $this->request_body = http_build_query($params, '', '&');
    
    $this->executePOST();
    
    if ( $this->response_info['http_code'] == self::HTTP_CODE_CREATED ) {
      $responseObject = json_decode($this->response_body);
      if(count($responseObject->SMSMessageData->Recipients) > 0)
    return $responseObject->SMSMessageData->Recipients;
      
      // throw new AfricasTalkingGatewayException($responseObject->SMSMessageData->Message);
    }
    return null;
    // throw new AfricasTalkingGatewayException($this->response_body);
  }
  

  public function fetchMessages($last_received_id)
  {
    $username = $this->api_username;
    $this->request_url = $this->getSendSmsUrl().'?username='.$username.'&lastReceivedId='. intval($last_received_id);
    
    $this->executeGet();
         
    if ( $this->response_info['http_code'] == self::HTTP_CODE_OK ) {
      $responseObject = json_decode($this->response_body);
      return $responseObject->SMSMessageData->Messages;
    }
    
    throw new AfricasTalkingGatewayException($this->response_body);    
  }
  
  private function executeGet ()
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HTTPHEADER, array ('Accept: application/json',
                             'apikey: ' . $this->api_key));
    $this->doExecute($curl);
  }
  
  private function executePost ()
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->request_body);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array ('Accept: application/json',
                             'apikey: ' . $this->api_key));
    
    $this->doExecute($curl);
  }
  
  private function executeJsonPost ()
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->request_body);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                           'Content-Length: ' . strlen($this->request_body),
                           'apikey: ' . $this->api_key));
    $this->doExecute($curl);
  }
  
  private function doExecute (&$curl_handle)
  {
    try {
        
      $this->setCurlOpts($curl_handle);
      $responseBody = curl_exec($curl_handle);
                
      if ( self::Debug ) {
    echo "Full response: ". print_r($responseBody, true)."\n";
      }
                
      $this->response_info = curl_getinfo($curl_handle);
                
      $this->response_body = $responseBody;
      curl_close($curl_handle);
    }
       
    catch(Exeption $e) {
      curl_close($curl_handle);
      throw $e;
    }
  }
  
  private function setCurlOpts (&$curl_handle)
  {
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl_handle, CURLOPT_URL, $this->request_url);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
  }

  private function getApiHost() {
    return ($this->environment == 'sandbox') ? 'https://api.sandbox.africastalking.com' : 'https://api.africastalking.com';
  }
  
  private function getSendSmsUrl($ait_extension = "") {
    return $this->getApiHost().'/version1/messaging'.$ait_extension;
  }
}
