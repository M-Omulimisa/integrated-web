<?php

namespace App\Services\OtpServices;

use App\Models\User;
use App\Traits\Notification;
use AfricasTalking\SDK\AfricasTalking;

/**
 * AfricaIsTalking SMS service handler
 *
 * @namespace tpaksu\LaravelOTPLogin\Services
 */
class AfricaIsTalking implements ServiceInterface
{
    /**
     * API key given by ait
     *
     * @var string
     */
    private $api_key;

    /**
     * Username for the API
     *
     * @var string
     */
    private $username;

    /**
     * The message to be send to the user
     *
     * @var [type]
     */
    private $message;

    /**
     * The User model's phone field name to be used for sending the SMS
     *
     * @var string
     */
    private $phone_column;

    /**
     * FROM number given by ait
     *
     * @var string
     */
    private $from;

    /**
     * Enables debug logging
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->from = config('otp.services.ait.from', "");
        $this->api_key = config('otp.services.ait.api_key', "");
        $this->username = config('otp.services.ait.username', "");
        $this->message = trans('otp.otp_message');
        $this->phone_column = config('otp.user_phone_field');
    }

    /**
     * Sends the generated password to the user and returns if it's successful
     *
     * @param App\User $user
     * @param string $otp
     * @param string $ref
     * @return boolean
     */
    public function sendOneTimePassword(User $user, $otp, $ref=null)
    {
        if (config('app.env') == "local") {
            logger(str_replace(":password", $otp, $this->message));
            return true;
        }

        // extract the phone from the user
        if ($this->debug) logger("entered AfricasTalkingGateway");
        $user_phone = data_get($user, $this->phone_column, false);

        // if the phone isn't set, return false
        if ($this->debug) logger("Phone $user_phone");
        if (!$user_phone) return false;

        // if the message isn't set, return false
        if ($this->debug) logger("Message ".$this->message);
        if (!$this->message) return false;

        try {
            $gateway = new AfricasTalking($this->username, $this->api_key);
            if ($this->debug) logger("Username ".$this->username);
            if ($this->debug) logger("Key ".$this->api_key);

            // Get one of the services
            $sms = $gateway->sms();

            // Use the service
            $response = $sms->send([ 'to' => $user_phone, 'message' => str_replace(":password", $otp, $this->message) ]);
            if ($this->debug) logger([$response]);

            // check if response contains the succeeded flag
            /* Response
            [
            'status' => 'success',
            'data' =>
            (object) array(
             'SMSMessageData' =>
            (object) array(
               'Message' => 'Sent to 1/1 Total Cost: UGX 35.0000',
               'Recipients' =>
              array (
                0 =>
                (object) array(
                   'cost' => 'UGX 35.0000',
                   'messageId' => 'ATXid_86fc93049b655cbed681818d7ca6c444',
                   'messageParts' => 1,
                   'number' => '+256775666852',
                   'status' => 'Success',
                   'statusCode' => 101,
                ),
              ),
            ),
            ),
            ]*/
            if ($this->debug) logger("Status ".$response['status']);
            return $response['status'] == "success" || $response['status'] == "sent";

        } catch (\Throwable $exception) {
            if ($this->debug) logger($exception->getMessage());
            // return false if any exception occurs
            return false;

        } catch (\Exception $e) {
            if ($this->debug) logger($e->getMessage());
            // return false if any exception occurs
            return false;
        }
    }

    public function sendTextMessage($phone, $message)
    {
        if (config('app.env') == "local") {
            logger(config('otp.otp_default_service'));
            logger($message);
            return true;
        }

        // extract the phone from the user
        if ($this->debug) logger("entered AfricasTalkingGateway");

        // if the phone isn't set, return false
        if ($this->debug) logger("Phone $phone");
        if (!$phone) return false;

        // if the message isn't set, return false
        if ($this->debug) logger("Message ".$message);
        if (is_null($message)) return false;

        try {
            $gateway = new AfricasTalking($this->username, $this->api_key);
            if ($this->debug) logger("Username ".$this->username);
            if ($this->debug) logger("Key ".$this->api_key);

            // Get one of the services
            $sms = $gateway->sms();

            // Use the service
            $response = $sms->send([ 'to' => $phone, 'message' => $message ]);
            if ($this->debug) logger([$response]);

            if(!isset($response['status'])) return false;

            if ($this->debug) logger("Status ".$response['status']);
            return $response['status'] == "success" || $response['status'] == "sent";

        } catch (\Throwable $exception) {
            if ($this->debug) logger($exception->getMessage());
            // return false if any exception occurs
            return false;

        } catch (\Exception $e) {
            if ($this->debug) logger($e->getMessage());
            // return false if any exception occurs
            return false;
        }
    }
}