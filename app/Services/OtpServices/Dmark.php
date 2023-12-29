<?php

namespace App\Services\OtpServices;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Notifications\SendUserOTPNotification;
use App\Traits\DmarkSms;

/**
 * Dmark SMS service handler
 *
 * @namespace tpaksu\LaravelOTPLogin\Services
 */
class Dmark implements ServiceInterface
{
    /**
     * Password given by dmark
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
     * The User model's 2 factor auth field name to be used
     *
     * @var string
     */
    private $auth_column;

    /**
     * The User model's email field name to be used for sending the message
     *
     * @var string
     */
    private $email_column;

    /**
     * FROM number given by dmark
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
        $this->from = config('otp.services.dmark.from', "");
        $this->api_key = config('otp.services.dmark.api_key', "");
        $this->username = config('otp.services.dmark.username', "");
        $this->message = trans('otp.otp_message');
        $this->phone_column = config('otp.user_phone_field');
        $this->email_column = config('otp.user_email_field');
        $this->auth_column = config('otp.user_auth_field');
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
        }
        logger(str_replace(":password", $otp, $this->message));
        return true;

        $factor_auth = data_get($user, $this->auth_column, false);

        if ($factor_auth == 'SMS') {

            // extract the phone from the user
            if ($this->debug) logger("entered DmarkGateway");
            $user_phone = data_get($user, $this->phone_column, false);

            // if the phone isn't set, return false
            if ($this->debug) logger("Phone $user_phone");
            if (!$user_phone) return false;
        }
        else{

            // extract the email from the user
            if ($this->debug) logger("entered DmarkGateway");
            $user_email = data_get($user, $this->email_column, false);

            // if the phone isn't set, return false
            if ($this->debug) logger("Phone $user_phone");
            if (!$user_email) return false;
        }

        // if the message isn't set, return false
        if ($this->debug) logger("Message ".$this->message);
        if (!$this->message) return false;

        try {
            if ($factor_auth == 'SMS') {
                $gateway = new DmarkSms($this->username, $this->api_key);
                if ($this->debug) logger("Username ".$this->username);
                if ($this->debug) logger("Key ".$this->api_key);

                // Use the service
                $response = $gateway->send($user_phone, str_replace(":password", $otp, $this->message));
                if ($this->debug) logger([$response]);

                if(!isset($response[0])) return false;

                if ($this->debug) logger(["Status" => $response[0]->status]);
                return $response[0]->status == "Success" || $response[0]->status == "Sent";
            }
            else{

                $request = new Request;

                $request->email = $user_email;
                $request->name = $user->name;
                $request->message = str_replace(":password", $otp, $this->message);
                
                Notification::route('mail', $request->email)->notify(new SendUserOTPNotification($request));

                return true;
            }

        } catch (\Throwable $exception) {
            if ($this->debug) logger($exception->getMessage());
            \Log::error($exception->getMessage());
            // return false if any exception occurs
            return false;

        } catch (\Exception $e) {
            if ($this->debug) logger($e->getMessage());
            // return false if any exception occurs
            \Log::error($e->getMessage());
            return false;
        }
    }

    public function sendTextMessage($phone, $message)
    {
        // if (config('app.env') == "local") {            
        //     logger(config('otp.otp_default_service'));
        //     logger($message);
        //     return true;
        // }

        // extract the phone from the user
        if ($this->debug) logger("entered DmarkGateway");

        // if the phone isn't set, return false
        if ($this->debug) logger("Phone $phone");
        if (!$phone) return false;

        // if the message isn't set, return false
        if ($this->debug) logger("Message ".$message);
        if (is_null($message)) return false;

        try {
            $gateway = new DmarkSms($this->username, $this->api_key);
            if ($this->debug) logger("Username ".$this->username);
            if ($this->debug) logger("Key ".$this->api_key);

            // Use the service
            $response = $gateway->send($phone, $message);
            if ($this->debug) logger([$response]);

            if(!isset($response[0])) return false;

            if ($this->debug) logger(["Status" => $response[0]->status]);

            $result = $response[0]->status == "Success" || $response[0]->status == "Sent";

            if(!$result) \Log::error(["DmarkAPI" => 'Message not sent']);

            return $result;

        } catch (\Throwable $exception) {
            if ($this->debug) \Log::error($exception->getMessage());
            // return false if any exception occurs
            return false;

        } catch (\Exception $e) {
            if ($this->debug) \Log::error($e->getMessage());
            // return false if any exception occurs
            return false;
        }
    }
}