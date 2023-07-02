<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use App\Models\BaseModel;
// use App\Mail\OutGoing;
// use Mail;

class NotificationService extends BaseModel
{
    /**
     * constructor
     */
    public function __construct() { }

    /**
     * Sends the message to the recipient and returns if it's successful
     *
     * @param string $phone number
     * @param string $message
     * @return boolean
     */
    public static function sendTextMessage($receiver, $message)
    {
        if (config('app.env') == "local") {
            logger($message);
            return true;
        }

        $debug          = true;
        $sms_username   = config('settings.ait.username', "");
        $sms_api_key    = config('settings.ait.key', "");

        // if the message isn't set, return false
        if ($debug) logger("Message ".$message);
        if (!$message) return false;

        // if the receiver isn't set, return false
        if ($debug) logger("Receiver ".$receiver);
        if (!$receiver) return false;

        try {
            $gateway = new AfricasTalking($sms_username, $sms_api_key);
            if ($debug) logger("Username ".$sms_username);
            if ($debug) logger("Key ".$sms_api_key);

            // Get one of the services
            $sms = $gateway->sms();

            // Use the service
            $response = $sms->send([ 'to' => $receiver, 'message' => $message ]);
            if ($debug) logger($response);

            // check if response contains the succeeded flag
            if ($debug) logger("Status ".$response['status']);
            return $response['status'] == "success" || $response['status'] == "sent";

        } catch (\Throwable $exception) {
            if ($debug) logger($exception->getMessage());
            // return false if any exception occurs
            return false;

        } catch (\Exception $e) {
            if ($debug) logger($e->getMessage());
            // return false if any exception occurs
            return false;
        }
    }

    public static function sendResetPassword($email, $token, $name)
    {
        $params['to'] = $email;
        $params['name'] = $name;
        $params['view'] = 'email-templates.reset_password';
        $params['body'] = 'Hello <d></d>, ' . trans('strings.reset_password_body') . ' <br><br><a href="' . config('settings.server.domain').'/set-password/' . $token . '">Click here to set a new password</a>';
        $params['subject'] = config('app.name') . ' ' . trans('strings.reset_password_request');

        self::sendMail($params);
    }

    public static function ticket_opened($email, $name = null)
    {
        $params['to'] = $email;
        $params['name'] = $name;
        $params['view'] = 'email-templates.reset_password';
        $params['body'] = trans('strings.ticket_created') . "<a href='" . config('settings.server.domain') . "'>" . trans('strings.reply_ticket') . "</a>";
        $params['subject'] = config('app.name') . ' ' . trans('strings.ticket_created');
        self::sendMail($params);
    }

    /**
     * @param $email
     * @param null $name
     */
    public static function sendInvitation($email, $name = null)
    {
        $params['to'] = $email;
        $params['name'] = $name;
        $params['body'] = trans('strings.ticket_created') . "<a href='" . config('settings.server.domain') . "/accept-invitation'>" . trans('strings.invitation') . "</a>";
        $params['subject'] = config('app.name') . ' ' . trans('strings.ticket_created');
        self::sendMail($params);
    }

    public static function sendMail($params)
    {
        // Mail::to($params['to'])->send(new OutGoing($params));
    }
}
