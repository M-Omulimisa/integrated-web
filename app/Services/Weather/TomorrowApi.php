<?php

namespace App\Services\Weather;

use Log;
use GuzzleHttp\Client as GuzzleClient;

/**
 * 
 */
class TomorrowApi
{
    /**
     * The API Key
     * Required.
     * @var string
     */
    private $key;

    /**
    * Set the Key
    * @param string $key 
    * @return void
    */
    public function set_key($key){
        $this->key = $key;
    }

    /**
     * Returns the Key
     * @return string 
     */
    public function get_key(){
        return $this->key;
    }

    /**
     * The API URL
     * Required.
     * Default: "https://api.tomorrow.io"
     * @var string
     */
    private $host = "https://api.tomorrow.io";

    /**
    * Set the URL
    * @param string $tomorrowUrl The URL to submit API requests to
    * @return void
    */
    public function set_URL($tomorrowUrl) {
        $this->host = $tomorrowUrl;
    }

    /**
     * Returns the URL
     * @return string 
     */
    public function get_URL(){
        return $this->host;
    }

    /**
     * API constructor.
     * @param string $host
     * @param string $key
     */
    public function __construct() { }

    /**
    * Request 
    * This method is used to Get Person Details using NIN.
    * @param  
    * @return array
    */
    public function forecast($latitude, $longitude, $timesteps, $metrics=null)
    {
        $result = $errorCode = $results = $apiCode = $apiErrorType = null;
        $status = 'PENDING';
        $errorResponseMsg = '';
        $payLoad = 'null';

        try {

            $headers = [
                'Content-Type' => 'application/json',
            ];

            $client = new GuzzleClient([
                'headers' => $headers,
                // 'verify' => TRUE
            ]);         

            $response = $client->get($this->get_URL().'/v4/weather/forecast?location='.$latitude.','.$longitude.'&timesteps='.$timesteps.'&apikey='.$this->get_key(), [
                            'timeout' => 15, // Response timeout
                            'connect_timeout' => 15, // Connection timeout
                        ]); 
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        } 
        catch (\GuzzleHttp\Exception\ConnectException $e) {
            $payLoad = $e->getMessage();
            $errorResponseMsg = 'Connection Problem #TMRW001';
            $errorCode = "CONNECTION_PROBLEM";
        }
        catch (\GuzzleHttp\Exception\ServerException $e) {
            $response = $e->getResponse();
            $errorCode = "UNEXPECTED_ERROR";
        }

        if (isset($response)) {
            $responseBodyAsString = $response->getBody()->getContents();
            $responseJson = json_decode($responseBodyAsString); 

            $responseStatusCode = $response->getStatusCode();
        }

        if (!isset($response) || $response === false || is_null($response) || $response == '') {
            $payLoad = $responseBodyAsString ?? $payLoad;
            $status = 'FAILED';

            if (strlen($errorResponseMsg)==0) {
                $errorResponseMsg = "Connection Problem #TMRW002";
                $errorCode = "CONNECTION_PROBLEM";
            }
            
            if (isset($responseStatusCode)) {
                if ($responseStatusCode==404) {
                    $errorCode = "NOT_FOUND";
                    $errorResponseMsg = "Resource not found";
                }
                elseif (strpos($payLoad, 'timed out') !== false) {
                    $errorResponseMsg = "Conectivity Problem #TMRW003";
                    $errorCode = "CONNECTION_PROBLEM";
                }
            }

            $errorResponseMsg = strtoupper($errorResponseMsg);
            $errorCode = $errorCode ?? "ERROR";

            if(strlen($errorResponseMsg)!=0) \Log::error(['Forecast' => $errorResponseMsg, 'Message' => $payLoad]);
        } 
        else {

            if ($responseStatusCode == 200) {
                $result = $responseJson;
                $status = 'SUCCESSFUL';

                if (isset($result->timelines)) {
                  $results = $result->timelines;
                }
                else {
                    $status = 'FAILED';
                }
            }
            else{

                $status = 'FAILED';
                $errorCode = $responseStatusCode;
                $responseFailureJson = json_decode($responseBodyAsString); 

                $apiCode = $responseStatusCode;
                $apiErrorType = $responseStatusCode;
                $errorResponseMsg = $responseFailureJson;

                if ($errorCode == 404) {
                }
                elseif ($errorCode == 400) {
                }
                elseif ($errorCode == 500) {
                }
                elseif ($errorCode == 401) {
                }

                $errorCode = $apiCode;
            }

            if(strlen($errorResponseMsg)!=0) \Log::error(['Forecast' => $errorResponseMsg, 'Message' => $payLoad]);
        }


        return (object) [
            'status'          => $status,
            'error_message'   => $errorResponseMsg ?? null,
            'error_code'      => $errorCode ?? null,
            'forecast'          => $results ?? null
        ];   
    }
}


