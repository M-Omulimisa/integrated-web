<?php

namespace App\Models;

use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    use HasFactory;

    //boot
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
        $u = User::find($this->user_id);
        if ($u == null) {
            $this->sms_sent = 'Failed because user not found';
            $this->save();
            return;
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
        $u = User::find($this->user_id);
        if ($u == null) {
            $this->email_sent = 'Failed because user not found';
            $this->save();
            return;
        }

        //check if email is valid
        if (!filter_var($u->email, FILTER_VALIDATE_EMAIL)) {
            $this->email_sent = 'Failed because user email is not valid';
            $this->save();
            return;
        }

        $data['body'] = $this->body;
        //$data['view'] = 'mails/mail-1';
        $data['data'] = $data['body'];
        $data['name'] = $u->name;
        $data['email'] = $u->email;
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
            //return;
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
            $this->save();
        } catch (\Throwable $th) {
            $this->notification_sent = 'Failed';
            $this->save();
        }
    }
}
