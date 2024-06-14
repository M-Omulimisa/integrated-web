<?php

namespace App\Api\v1;

use Log;
use Illuminate\Http\Request;
use App\Models\Payment\MtnApi;

/**
 * 
 */
class MtnPay
{
    protected $_sp_id;
    protected $_sp_username;
    protected $_sp_password;

    protected $debit_id;
    protected $_sp_debit_username;
    protected $_sp_debit_password;

    protected $_ecw_url;

    protected $_sp_certificate;
    protected $_sp_cert_password;

    public function __construct()
    {
        $dedit                      = MtnApi::where('status', true)->where('api', 'debit')->first();
        $sptransfer                 = MtnApi::where('status', true)->where('api', 'sptransfer')->first();

        if ($dedit && $sptransfer) {
            $this->_sp_id               = $sptransfer->id;
            $this->_sp_username         = $sptransfer->decryptData('username');
            $this->_sp_password         = $sptransfer->decryptData('password');

            $this->debit_id             = $dedit->id;
            $this->_sp_debit_username   = $dedit->decryptData('username');
            $this->_sp_debit_password   = $dedit->decryptData('password');

            $this->_ecw_url             = $dedit->url;

            $this->_sp_certificate      = base_path() . '/public'.$dedit->certificate;
            $this->_sp_cert_password    = base_path() . '/public'.$dedit->key;
        }
    }

    public  function postXml($xml, $username, $password, $api_url) {

            $auth        = $username . ':' . $password;
            $credentials = base64_encode($auth);
    
            $headers   = array();
            $headers[] = "Content-type: application/xml";
            $headers[] = "Authorization: Basic " . $credentials;

            $ch = curl_init(); 

            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $this->_ecw_url.$api_url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSLCERT, $this->_sp_certificate);
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->_sp_cert_password);

            return $ch;
       }

    public function debit($amount, $phoneNumber, $omul_transactionid, $frommessage)
    {

    /**ERRORS
    * Debit
    * <?xml version="1.0" encoding="UTF-8"?><ns0:errorResponse xmlns:ns0="http://www.ericsson.com/lwac" errorcode="INTERNAL_ERROR"/>

    <?xml version="1.0" encoding="UTF-8"?><ns0:errorResponse xmlns:ns0="http://www.ericsson.com/lwac" errorcode="REFERENCE_ID_ALREADY_IN_USE"/>

    <?xml version="1.0" encoding="UTF-8"?><ns0:errorResponse xmlns:ns0="http://www.ericsson.com/lwac" errorcode="TARGET_AUTHORIZATION_ERROR"/>
    *
    */
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml = $xml . '<ns0:debitrequest xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_0">';
        $xml = $xml . '<fromfri>FRI:'.$phoneNumber.'/MSISDN</fromfri>';
        $xml = $xml . '<tofri>FRI:'.$this->_sp_debit_username.'/USER</tofri>';
        $xml = $xml . '<amount>';
        $xml = $xml . '<amount>'.$amount.'</amount>';
        $xml = $xml . '<currency>UGX</currency>';
        $xml = $xml . '</amount>';
        $xml = $xml . '<externaltransactionid>'.$omul_transactionid.'</externaltransactionid>';
        $xml = $xml . '<frommessage>'.$frommessage.'</frommessage>';
        $xml = $xml . '<referenceid>'.$omul_transactionid.'</referenceid>';
        $xml = $xml . '</ns0:debitrequest>';

        Log::useFiles(base_path() . '/storage/logs/ecw_debit.log');

        Log::info(['debitRequest' => $xml]);

        $curl_request = $this->postXml($xml, $this->_sp_debit_username, $this->_sp_debit_password, '/v1/debit');

        $response = curl_exec($curl_request);

        $result = null;
        $status = 'FAILED';
        $transactionid = null;
        $errorResponseMsg = '';
        $duplicateRef = false;

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            Log::info(['ResponseError' => $response, 'FailedResponseMsg' => curl_error($curl_request)]);
        } else {  

                if (stripos($response, 'HTTP') !== false && stripos($response, 'xml') !== false) {
                    $response = strstr($response, '<?xml');
                }

                Log::info(['Response' => $response]);

                $validated_response = $this->isValidXML($response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200 && $validated_response) {
                    $content = $this->isBlankResponse($response);
                    $result = $content >= 0 ? $response : null ;
                }
                else{
                    //failed transaction error messages handled here
                    //TARGET_NOT_FOUND
                    if (stripos($response, 'REFERENCE_ID_ALREADY_IN_USE')) {
                        $duplicateRef = true;
                    }
                    elseif (stripos($response, 'TARGET_AUTHORIZATION_ERROR')) {
                        $errorResponseMsg   = "Insufficient funds on target account";
                        $status             = "FAILED";
                    }
                }
                Log::info(['debitResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }  

            curl_close($curl_request);

            if (!is_null($result)) {
                $processedXML       = $this->processXML($result);
                $transactionid      = $processedXML->transactionid;
                $status             = $processedXML->status;
                $errorResponseMsg   = '';           
            }

                $transaction                     = new Request;
                $transaction->transactionid      = $transactionid;
                $transaction->status             = $status;
                $transaction->apid               = $this->debit_id;
                $transaction->errorMsg           = $errorResponseMsg;
                $transaction->duplicateRef       = $duplicateRef; 
                return $transaction;
    }

    public function getTransactionStatus($referenceid, $type)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml = $xml . '<ns1:gettransactionstatusrequest xmlns:ns1="http://www.ericsson.com/em/emm/financial/v1_1">';
        $xml = $xml . '<referenceid>'.$referenceid.'</referenceid>';
        $xml = $xml . '</ns1:gettransactionstatusrequest>'; 

        /*
        * Reponse format
        <?xml version="1.0" encoding="UTF-8"?><ns0:gettransactionstatusresponse xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_1">
            <financialtransactionid>2968597</financialtransactionid>
            <status>SUCCESSFUL</status>
        </ns0:gettransactionstatusresponse>

        * Error response
        <?xml version="1.0" encoding="UTF-8"?><ns0:errorResponse xmlns:ns0="http://www.ericsson.com/lwac" errorcode="TRANSACTION_NOT_FOUND"/>

        <?xml version="1.0" encoding="UTF-8"?><ns0:errorResponse xmlns:ns0="http://www.ericsson.com/lwac" errorcode="REFERENCE_ID_NOT_FOUND"><arguments name="actual" value="252501"/></ns0:errorResponse>
        */

        Log::useFiles(base_path() . '/storage/logs/ecw_debit.log');

        Log::info(['getTxnStatusRequest' => $xml]);

        if ($type == 'sptransfer') {
            $user_name = $this->_sp_username;
            $pass_word = $this->_sp_password;
        }else{
            $user_name = $this->_sp_debit_username;
            $pass_word = $this->_sp_debit_password;
        }
            $curl_request = $this->postXml($xml, $user_name, $pass_word, '/v1_1/gettransactionstatus');

        $response = curl_exec($curl_request);

        $result = null;
        $status = 'PENDING';
        $refrenceid = null;
        $errorResponseMsg = '';

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            Log::info(['ResponseError' => $response, 'FailedResponseMsg' => curl_error($curl_request)]);
        } else {  

                if (stripos($response, 'HTTP') !== false && stripos($response, 'xml') !== false) {
                    $response = strstr($response, '<?xml');
                }

                Log::info(['getTxnStatusResponse' => $response]);

                $validated_response = $this->isValidXML($response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200 && $validated_response) {
                    $content = $this->isBlankResponse($response);
                    $result = $content >= 0 ? $response : null ;
                }
                else{
                    //failed transaction error messages handled here
                    if (stripos($response, 'TRANSACTION_NOT_FOUND') || stripos($response, 'REFERENCE_ID_NOT_FOUND')) {
                        $status = 'UNKNOWN';
                    }
                }
                Log::info(['statusResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }  

            curl_close($curl_request);

            if (!is_null($result)) {
                $processedXML       = $this->processXML($result);
                $refrenceid         = $processedXML->financialtransactionid;
                $status             = $processedXML->status;
                $errorResponseMsg   = '';           
            }

                $transaction                    = new Request;
                $transaction->refrenceid        = $refrenceid;
                $transaction->status            = $status;
                $transaction->errorMsg          = $errorResponseMsg; 

                return $transaction;
    }

    public function getAccountHolderInfo($phonenumber)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml = $xml . '<ns2:getaccountholderinforequest xmlns:ns2="http://www.ericsson.com/em/emm/provisioning/v1_1">';
        $xml = $xml . '<identity>ID:'.$phonenumber.'/MSISDN</identity>';
        $xml = $xml . '</ns2:getaccountholderinforequest>';

        /*
        * Reponse format
        <?xml version="1.0" encoding="UTF-8"?><ns0:getaccountholderinforesponse xmlns:ns0="http://www.ericsson.com/em/emm/provisioning/v1_1">
            <accountholderbasicinfo>
                <msisdn>256775666852</msisdn>
                <firstname>Najja</firstname>
                <surname>.</surname>
                <accountholderstatus>ACTIVE</accountholderstatus>
            </accountholderbasicinfo>
        </ns0:getaccountholderinforesponse>
        *
        * Error
        <?xml version="1.0" encoding="UTF-8"?><ns0:errorResponse xmlns:ns0="http://www.ericsson.com/lwac" errorcode="ACCOUNTHOLDER_NOT_FOUND"><arguments name="id" value="256784684564"/></ns0:errorResponse>
        */

        Log::useFiles(base_path() . '/storage/logs/ecw_accountholder.log');

        Log::info(['accountHolderRequest' => $xml]);

        $curl_request = $this->postXml($xml, $this->_sp_username, $this->_sp_password, '/v1_1/getaccountholderinfo');

        $response   = curl_exec($curl_request);
        $result     = null;

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            Log::info([
                        'FailedResponse' => 'cURL Transport Error (HTTP request failed): '.curl_error($curl_request)]);
        } else {  

                if (stripos($response, 'HTTP') !== false && stripos($response, 'xml') !== false) {
                    $response = strstr($response, '<?xml');
                }

                // Log::info(['Response' => $response]);

                $validated_response = $this->isValidXML($response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200 && $validated_response) {
                    $content = $this->isBlankResponse($response);
                    $result = $content >= 0 ? $response : null ;
                }
                Log::info(['accounInfoResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }   

            curl_close($curl_request); 

            $merchant                       = new Request;
            $merchant->errorResponseMsg     = null;

            if (!is_null($result)) {
                $processedXML                   = $this->processXML($result);
                $merchant->msisdn               = (string) $processedXML->accountholderbasicinfo->msisdn;
                $merchant->firstname            = (string) $processedXML->accountholderbasicinfo->firstname;
                $merchant->surname              = (string) $processedXML->accountholderbasicinfo->surname;
                $merchant->accountholderstatus  = (string) $processedXML->accountholderbasicinfo->accountholderstatus;                             
            }
            else{
                $merchant->msisdn               = '';
                $merchant->firstname            = '';
                $merchant->surname              = '';
                $merchant->accountholderstatus  = ''; 
            }

            if ($responseStatusCode == 500) {
                if (stripos($response, 'ACCOUNTHOLDER_NOT_FOUND')) {
                    $merchant->errorResponseMsg = 'Accountholder information not found';
                }
            }

            // Log::info(['merchant' => $merchant]);

        return $merchant;
    }

    public function getAccountBalance($type)
    {

        if ($type == 'transfers') {
            $user_name = $this->_sp_username;
            $pass_word = $this->_sp_password;
        }else{
            $user_name = $this->_sp_debit_username;
            $pass_word = $this->_sp_debit_password;
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml = $xml . '<ns0:getbalancerequest xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_0">';
        $xml = $xml . '<fri>FRI:'.$user_name.'/USER</fri>';
        $xml = $xml . '</ns0:getbalancerequest>'; 

        /*
        * Reponse format
        <?xml version="1.0" encoding="UTF-8"?>
        <ns0:getbalanceresponse xmlns:ns0="http://www.ericsson.com/em/emm/financial/v1_0">
            <balance>
                <amount>4974000.00</amount>
                <currency>UGX</currency>
            </balance>
            <loyalty/>
        </ns0:getbalanceresponse>
        */

        Log::useFiles(base_path() . '/storage/logs/ecw_balance.log');

        Log::info(['getBalanceRequest' => $xml]);
        
        $curl_request = $this->postXml($xml, $user_name, $pass_word, '/v1/getbalance');

        $response = curl_exec($curl_request);
        $result = null;

        //Log::info(['balanceResponse1' => $response]);

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false; 
            Log::info([
                        'FailedResponse' => 'cURL Transport Error (HTTP request failed): '.curl_error($curl_request)]);
        } else {  

                if (stripos($response, 'HTTP') !== false && stripos($response, 'xml') !== false) {
                    $response = strstr($response, '<?xml');
                }

                //Log::info(['Response' => $response]);

                $validated_response = $this->isValidXML($response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200 && $validated_response) {
                    $content = $this->isBlankResponse($response);
                    $result = $content >= 0 ? $response : null ;
                }
                Log::info(['balanceResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }   

            curl_close($curl_request); 

            $balance           = new Request;

            if (!is_null($result)) {
                $processedXML  = $this->processXML($result);
                $balance->amount        = (int) $processedXML->balance->amount;
                $balance->currency      = (string) $processedXML->balance->currency;                        
            }
            else{
                $balance->amount      = 'Response Error'; 
                if ($responseStatusCode == 500 || $responseStatusCode == 401) {
                   if (stripos($response, 'UNAUTHORIZED')) {
                    $balance->amount = 'Unauthorized Access';
                }              
                    $balance->currency    = '';
                }
            }
        
        return $balance;
    }

    public function makePayment($firstname, $lastname, $recipient, $amount, $payout_id, $message, $reference_id)
    {
        $username = $this->_sp_username;
        $password = $this->_sp_password;

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml = $xml . '<ns0:sptransferrequest xmlns:ns0="http://www.ericsson.com/em/emm/serviceprovider/v1_0/backend">';
        $xml = $xml . '<sendingfri>FRI:'.$username.'/USER</sendingfri>';
        $xml = $xml . '<receivingfri>FRI:' . $recipient . '/MSISDN</receivingfri>';
        $xml = $xml . '<amount>';
        $xml = $xml . '<amount>' . $amount . '</amount>';
        $xml = $xml . '<currency>UGX</currency>';
        $xml = $xml . '</amount>';
        $xml = $xml . '<providertransactionid>' . $payout_id . '</providertransactionid>';
        $xml = $xml . '<name>';
        $xml = $xml . '<firstname>' . $firstname . '</firstname>';
        $xml = $xml . '<lastname>' . $lastname . '</lastname>';
        $xml = $xml . '</name>';
        $xml = $xml . '<sendernote>Bank pull request</sendernote>';
        $xml = $xml . '<receivermessage>' . $message . '</receivermessage>';
        $xml = $xml . '<referenceid>'.$reference_id.'</referenceid>';
        $xml = $xml . '</ns0:sptransferrequest>';

        Log::info(['spTransferRequest' => $xml]);

        $curl_request = $this->postXml($xml, $username, $password, '/v1/sptransfer');

        $response   = curl_exec($curl_request);

        $result = null;
        $status = 'FAILED';
        $errorResponseMsg = null;

        if (curl_error($curl_request) || $response === false) {
            $responseStatusCode = false;
            $errorResponseMsg= 'Connection problem'; 
            Log::info([
                        'FailedResponse' => 'cURL Transport Error (HTTP request failed): '.curl_error($curl_request)]);
        } else {  

                if (stripos($response, 'HTTP') !== false && stripos($response, 'xml') !== false) {
                    $response = strstr($response, '<?xml');
                }

                Log::info(['Response' => $response]);

                $validated_response = $this->isValidXML($response);
                $info = curl_getinfo($curl_request);
                $responseStatusCode = $info['http_code']; 

                if ($responseStatusCode == 200 && $validated_response) {
                    $content = $this->isBlankResponse($response);
                    $result = $content >= 0 ? $response : null ;
                    $status = 'SUCCESSFUL';
                }
                else{
                    if (stripos($response, 'NOT_ENOUGH_FUNDS')) {
                        $errorResponseMsg = 'Insufficient funds on your transfering account';
                    }
                    elseif (stripos($response, 'RESOURCE_NOT_FOUND')) {
                        $errorResponseMsg = 'Merchant with phone number '.$recipient.' not found';
                    }
                    elseif (stripos($response, 'TARGET_NOT_FOUND')) {
                        $errorResponseMsg = 'Target account not found';
                    }
                    elseif (stripos($response, 'AUTHORIZATION_RECEIVING_ACCOUNT_NOT_ACTIVE')) {
                        $errorResponseMsg = 'Mobile money account not active';
                    }
                    elseif (stripos($response, 'AUTHORIZATION_SENDER_ACCOUNT_NOT_ACTIVE')) {
                        $errorResponseMsg = 'Your transfer account not active';
                    }
                    elseif (stripos($response, 'SOURCE_NOT_FOUND')) {
                        $errorResponseMsg = 'Source account doesnot exist';
                    }
                    //SOURCE_NOT_FOUND
                }
                Log::info(['sptransferResponse' => $result, 'responseStatusCode' => $responseStatusCode]);
            }  

            curl_close($curl_request); 

            $transfer           = new Request;

            if (!is_null($result)) {
                $processedXML  = $this->processXML($result);
                $transfer->transactionid = (string) $processedXML->transactionid;           
            }
            else{
                 $transfer->transactionid = null;
            }
                $transfer->status           = $status;
                $transfer->apid             = $this->_sp_id;
                $transfer->errorResponse    = $errorResponseMsg;

        return $transfer;
    }

    /**
     * Checks if response from ECW is a blank response
     * 
     * @param String $response The XML response from ECW
     * @return Number       Count of the response contents
     */
    public function isBlankResponse($response)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);

        //Log::info(['xmlCount' => $xml->count()]);

        return $xml->count();
    }

    /**
     * Determines if the xml response sent from ECW is is a valid 
     * parseable response by interpreting the request string of type XML 
     * into an object and checking for errors
     * 
     * @return boolean     FALSE if it is not XML rather HTML or empty or if there are any errors 
     *                     TRUE if its a valid xml and parseable.
     */
    public function isValidXML($content)
    {

        $content = trim($content);
        if (empty($content)) {
            return false;
        }

        if (stripos($content, '<!DOCTYPE html>') !== false) {
            return false;
        }

        libxml_use_internal_errors(true);
        simplexml_load_string($content);
        $errors = libxml_get_errors();          
        libxml_clear_errors(); 

        //Log::info(['xmlValidation' => empty($errors)]); 

        return empty($errors);
    }

    /**
     * Enable libxml errors and allow user to fetch error information as needed 
     * Initiate with No Response
     * Check if XML string is well-formed
     * Interpret the request string of XML into an object if string has no erros
     * Set the Response to converted object
     * 
     * @throws XMLException catch error XML string is not well formed 
     * 
     * @return  Response
     */

    public function processXML($raw_XML){

        libxml_use_internal_errors(true);
        $response = false;
    
        try {
            $xml_response = simplexml_load_string($raw_XML);
            $response     = $xml_response;            
        } catch (Exception $ex) {
            $error_message = 'An Exception was caught by the system';
            foreach (libxml_get_errors as $error_line)
            {
                $error_message.="\t".$error_line->message;
            }
            trigger_error($error_message);
        }
        return $response;
    }
}