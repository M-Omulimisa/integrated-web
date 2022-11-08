<?php

namespace App\Traits;

use AfricasTalking\SDK\AfricasTalking;
use App\Traits\OutGoingMail;
use Mail;
use App\Models\BaseModel;

class Notification extends BaseModel
{

    /**
     * Sending text messages
     *
     * @param steing $receiver [comma seperated]
     * @param string $message
     *
     * @return object $result
     */
    public static function sendSMS($receiver, $message)
    {
        try {
            $data['receiver'] = $receiver;
            $data['message'] = $message;

            $username   = config('settings.ait.username'); // use 'sandbox' for development in the test environment
            $apiKey     = config('settings.ait.key'); // use your sandbox app API key for development in the test environment

            $AfricasTalking = new AfricasTalking($username, $apiKey);

            // Get one of the services
            $sms = $AfricasTalking->sms();

            // Use the service
            $result = $sms->send([
                'to'        => $receiver,
                'message'   => $message,
            ]);

            \Log::info(['Result' => $result]);

            return $result;
        } catch (\Throwable $exception) {
            return $exception->getMessage();
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
        Mail::to($params['to'])->send(new OutGoing($params));
    }
}
