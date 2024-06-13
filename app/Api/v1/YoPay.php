<?php

namespace App\Api\v1;

use Log;
use Illuminate\Http\Request;

/**
 * 
 */
class YoPay
{
    protected $_username;
    protected $_pay_password;

    public function __construct()
    {
        $this->_username      = config('yopay.username');
        $this->_pay_password  = config('yopay.password');
    }

    public  function postXml($xml) {
            $soap_do = curl_init(); 
            curl_setopt($soap_do, CURLOPT_URL,"https://paymentsapi2.yo.co.ug/ybs/task.php");   
            curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 120); 
            curl_setopt($soap_do, CURLOPT_TIMEOUT,        120); 
            curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($soap_do, CURLOPT_POST,           true ); 
            curl_setopt($soap_do, CURLOPT_POSTFIELDS,    $xml); 
            curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,    0); 
            curl_setopt($soap_do, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            //curl_setopt($soap_do, CURLOPT_VERBOSE,    TRUE); 
            curl_setopt($soap_do, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml', 'Content-transfer-encoding: text', 'Content-Length: '.strlen($xml) ));

            return curl_exec($soap_do);
       }

    public function pullDeposit($amount,$phoneNumber,$transactionId,$RefMessage, $providerCode, $nonBlocking='TRUE')
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <AutoCreate>
                    <Request>
                    <APIUsername>".$this->_username."</APIUsername>
                    <APIPassword>".$this->_pay_password."</APIPassword>
                        <Method>acdepositfunds</Method>
                        <NonBlocking>".$nonBlocking."</NonBlocking>
                        <Amount>".$amount."</Amount>
                        <Account>".$phoneNumber."</Account>
                        <AccountProviderCode>".$providerCode."</AccountProviderCode>
                        <Narrative>".$RefMessage."</Narrative>
                        <ExternalReference>".$transactionId."</ExternalReference>
                        <ProviderReferenceText>".$RefMessage."</ProviderReferenceText>
                    </Request>
                </AutoCreate>";

    Log::useFiles(base_path() . '/storage/logs/yo_payment.log');

    $sendToYo = $this->postXml($xml);

    libxml_use_internal_errors(true);
    $result=preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $sendToYo);

    Log::info(['result' => $result]);

    try {
        if ($result == '' || $result == null ) {
            $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "No response. Please contact customer support", "TransactionReference" => null));
        }else{
            $response = simplexml_load_string($result);            
        }
    } 
    catch (Exception $e) {
    $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "Unable to initiate payment. Please contact customer support", "TransactionReference" => null));
    
    Log::info(['DepositResponse' => $response]);
    }

    if ((string) ($response->{'Response'}->{'StatusCode'}) == "-40") {
    	/**
    	* Unsupported network
    	<?xml version="1.0" encoding="UTF-8"?>
    	<AutoCreate>
	    	<Response>
	    		<Status>ERROR</Status>
	    		<StatusCode>-40</StatusCode>
	    		<StatusMessage>Your deposit request failed. The PULL inbound deposit method is not supported by the network.</StatusMessage>
	    		<TransactionStatus/>
	    	</Response>
    	</AutoCreate>
    	*/
            $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "Your deposit request failed. The PULL inbound deposit method is not supported by the network.", "TransactionReference" => null));
    }

    $return=array(
    'statusMsg' => (string) ($response->{'Response'}->{'StatusMessage'}),
    'txnId' => (string) ($response->{'Response'}->{'TransactionReference'}));

    Log::info(['DepositInfo' => 'Reference returned via method: '.$return['txnId']]);
    return $return['txnId'];
    }

    public function getTransactionStatus($reference) {
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <AutoCreate>
                    <Request>
                    <APIUsername>".$this->_username."</APIUsername>
                    <APIPassword>".$this->_pay_password."</APIPassword>
                        <Method>actransactioncheckstatus</Method>
                        <TransactionReference>".$reference."</TransactionReference>
                    </Request>
                </AutoCreate>";

    Log::useFiles(base_path() . '/storage/logs/yo_payment.log');

    $sendToYo = $this->postXml($xml);

    libxml_use_internal_errors(true);
    $result=preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $sendToYo);

    try {
        if ($result == '' || $result == null ) {
            $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "No response. Please contact customer support", "TransactionReference" => null, "TransactionStatus" => 'FAILED'));
        }else{
            $response = simplexml_load_string($result);            
        }
    } 
    catch (Exception $e) {
    $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "Unable to initiate payment. Please contact customer support", "TransactionReference" => null));

    Log::info(['StatusResponse' => $response]);
    }

    $return=array(
    'status' => (string) ($response->{'Response'}->{'TransactionStatus'}),
    'statusMsg' => (string) ($response->{'Response'}->{'StatusMessage'}),
    'txnId' => (string) ($response->{'Response'}->{'TransactionReference'}));

    // Log::info(['Status' => $return['status']]);
    return $return['status'];
    }

    public function getNewStatus($dbReference)
       {
          //get yo! status
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <AutoCreate>
                <Request>
                    <APIUsername>".$this->_username."</APIUsername>
                    <APIPassword>".$this->_pay_password."</APIPassword>
                    <Method>actransactioncheckstatus</Method>
                    <TransactionReference>".$dbReference."</TransactionReference>
                </Request>
            </AutoCreate>";

            Log::useFiles(base_path() . '/storage/logs/yo_payment.log');

            $sendToYo = $this->postXml($xml);

            libxml_use_internal_errors(true);
            $result=preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $sendToYo);

            try {
                if ($result == '' || $result == null ) {
                    $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "No reponse. Please contact customer support", "TransactionReference" => null, "TransactionStatus" => 'FAILED'));
                }else{
                    $response = simplexml_load_string($result);            
                }
            } 
            catch (Exception $e) {
            $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "Unable to initiate payment. Please contact customer support", "TransactionReference" => null, "TransactionStatus" => 'FAILED'));
            }

            $return=array(
            'status' => (string) ($response->{'Response'}->{'TransactionStatus'}),
            'statusMsg' => (string) ($response->{'Response'}->{'StatusMessage'}),
            'txnId' => (string) ($response->{'Response'}->{'TransactionReference'}));

            // Log::info(['NewStatus' => $return['status']]);
            return $return['status'];
       }

    public function getAccountBalance() {
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <AutoCreate>
            <Request>
                <APIUsername>".$this->_username."</APIUsername>
                <APIPassword>".$this->_pay_password."</APIPassword>
                <Method>acacctbalance</Method>
            </Request>
        </AutoCreate>";

    Log::useFiles(base_path() . '/storage/logs/yo_payment.log');

    $sendToYo = $this->postXml($xml);

    libxml_use_internal_errors(true);
    $result=preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $sendToYo);

    Log::info(['StatusResponse' => $result]);

    if (!is_null($result) || $result !==false) {
        $response = simplexml_load_string($result);

            $balance           = new Request;

            if (stripos($result, 'not permitted')) {
                $balance->amount        = 'Access Denied';
                $balance->currency      = '';                        
            }
            else{
                /*<?xml version="1.0" encoding="UTF-8"?>
                <AutoCreate>
                    <Response>
                        <Status>OK</Status>
                        <StatusCode>0</StatusCode>
                        <Balance>
                            <Currency><Code>UGX-MTNMM</Code><Balance>62635.00</Balance></Currency>
                            <Currency><Code>UGX-WTLMM</Code><Balance>139083.00</Balance></Currency>
                        </Balance>
                    </Response>
                </AutoCreate>*/
                
                //$balance->amount      = (string) ($response->{'Response'}->{'Balance'}->{'Currency'}->{'Balance'});

                $total = 0;
                foreach ($response->{'Response'}->{'Balance'}->{'Currency'} as $value) {
                  $amount = $value->{'Balance'};
                  $total = $amount + $total;
                }

                $balance->amount = number_format($total);
                $balance->currency    = 'UGX';
            } 
        # code...
    }else{
        $response = (object) array("Response" => (object) array("Status" => "ERROR", "StatusMessage" => "Unable to initiate request. Please contact customer support", "TransactionReference" => null));
        $balance->amount      = 'Response Error';
        $balance->currency    = '';        
    }
    // Log::info(['Status' => $return['status']]);
    return $balance;
    }
}