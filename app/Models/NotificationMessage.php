<?php

namespace App\Models;

use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    use HasFactory;

    //boot
    //password from phpmyadmin: $2y$10$wjEIpBoax4BpJhqTdiCtMO7cmepyIYLxSu3c8eg35OS5iTigwG/AO
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if ($m->notification_campaign_id != null) {
                if ($m->notification_campaign_id != '') {
                    $exists = NotificationMessage::where('notification_campaign_id', $m->id)
                        ->where('user_id', $m->id)
                        ->first();
                    if ($exists) {
                        return false;
                    }
                }
            }
            //has sms
            if ($m->send_sms == 'Yes') {
                //user
                $u = User::find($m->user_id);
                if ($u == null) {
                    return false;
                }
                //phone
                $phone = Utils::prepare_phone_number($m->phone_number);
                if (Utils::phone_number_is_valid($phone)) {
                    //phone_number
                    $m->phone_number = $phone;
                }
            }

            //has email
            if ($m->send_email == 'Yes') {
                //user
                $u = User::find($m->user_id);
                if ($u == null) {
                    return false;
                }
                //email
                if (filter_var($u->email, FILTER_VALIDATE_EMAIL)) {
                    $m->email = $u->email;
                }
            }
        });
    }

    //send_now
    public function send_now()
    {
        if ($this->ready_to_send != 'Yes') {
            //return;
        }
        if ($this->send_notification == 'Yes') {
            $this->sendNotification();
        }
        if ($this->send_email == 'Yes') {
            $this->sendEmail();
        }
        if ($this->send_sms == 'Yes') {
            $this->sendSms();
        }
        $this->ready_to_send = 'Sent';
        $this->ready_to_send = 'Sent';
        $this->save();
    }


    public function sendSms()
    {
        if ($this->sms_sent != 'No') {
            return;
        }

        $phone = '';
        if ($this->phone_number != null) {
            $phone = Utils::prepare_phone_number($this->phone_number);
        }

        if (!Utils::phone_number_is_valid($phone)) {
            $u = User::find($this->user_id);
            if ($u == null) {
                $this->sms_sent = 'Failed because user not found';
                $this->save();
                return;
            }
        }

        //check if phone number is valid
        $phone = Utils::prepare_phone_number($this->phone_number);

        if (!Utils::phone_number_is_valid($phone)) {
            $this->sms_sent = 'Failed because user phone number is not valid';
            $this->save();
            return;
        }

        try {
            Utils::send_sms($phone, $this->sms_body);
            $this->sms_sent = 'Yes';
            $this->save();
        } catch (\Throwable $th) {
            $this->sms_sent = 'Failed because ' . $th->getMessage();
            $this->save();
        }
    }


    public function sendEmail()
    {
        if ($this->email_sent != 'No') {
            return;
        }

        $email = '';
        if ($this->email != null) {
            //validate email
            if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $email = $this->email;
            }
        }

        $u = User::find($this->user_id);
        if ($u == null) {
            $this->email_sent = 'Failed because user not found';
            $this->save();
            return;
        }
        //validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = $u->email;
        }

        //check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email_sent = 'Failed because user email is not valid';
            $this->save();
            return;
        }

        $this->email = $email;

        $data['body'] = $this->body;
        //$data['view'] = 'mails/mail-1';
        $data['data'] = $data['body'];
        $data['name'] = $u->name;
        $data['email'] = $email;
        $data['subject'] = $this->title . ' - M-Omulimisa';
        try {
            Utils::mail_sender($data);
            $this->email_sent = 'Yes';
            $this->save();
        } catch (\Throwable $th) {
            $this->email_sent = 'Failed';
            $this->save();
        }
    }
    public function sendNotification()
    {

        if ($this->notification_sent != 'No') {
            return;
        }
        $img = $this->image;
        //check image file exists
        if ($img != null) {
            $path = storage_path('public') . '/' . $img;
            if ($path) {
                $img = url('storage/' . $img);
            } else {
                $img = null;
            }
        } else {
            $img = null;
        }

        $params = [
            'msg' => $this->short_description,
            'headings' => $this->title,
            'receiver' => $this->user_id,
        ];
        
        if ($img != null) {
            $params['big_picture'] = $img;
        }


        //check if type is url 
        if (strtolower($this->type) == 'url') {
            if (filter_var($this->url, FILTER_VALIDATE_URL)) {
                $params['url'] = $this->url;
            }
        }

        try {
            Utils::sendNotification2($params);
            $this->notification_sent = 'Yes';
            $this->notification_seen = 'No';
            $this->notification_seen_time = null;

            $this->save();
        } catch (\Throwable $th) {
            $this->notification_sent = 'Failed because ' . $th->getMessage();
            throw $th;
            $this->save();

        }
    }
}
