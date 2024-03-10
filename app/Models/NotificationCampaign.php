<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationCampaign extends Model
{
    use HasFactory;

    //boot
    protected static function boot()
    {
        parent::boot();

        static::created(function ($notificationCampaign) {
            if ($notificationCampaign->ready_to_send == 'Yes') {
                $notificationCampaign->prepareSend();
            }
        });
        //udpated
        static::updated(function ($notificationCampaign) {
            if ($notificationCampaign->ready_to_send == 'Yes') {
                $notificationCampaign->prepareSend();
            }
        });
    }


    //getter for target_users
    public function getTargetUsersAttribute($value)
    {
        return json_decode($value);
    }

    //setter for title
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }

    //setter for target_users
    public function setTargetUsersAttribute($value)
    {
        if ($value == null || $value == '') {
            $value = [];
        }
        $this->attributes['target_users'] = json_encode($value);
    }

    //prepare send prepareSend
    public function prepareSend()
    {
        if ($this->ready_to_send != 'Yes') {
            return;
        }
        $users = [];
        if ($this->target_type == 'All') {
            $users = User::all();
        } elseif ($this->target_type == 'Role') {
            $admin_role = AdminRoleUser::where('role_id', $this->target_user_role_id)->get();
            foreach ($admin_role as $role) {
                $u = User::find($role->user_id);
                if ($u == null) {
                    continue;
                }
                $users[] = $u;
            }
        } elseif ($this->target_type == 'Users') {
            $users = User::whereIn('id', $this->target_users)->get();
        }
        if (count($users) < 1) {
            return;
        }


        foreach ($users as $key => $user) {
            $exists = NotificationMessage::where('notification_campaign_id', $this->id)
                ->where('user_id', $user->id)
                ->first();
            if ($exists != null) {
                continue;
            }
            $msg = new NotificationMessage();
            $msg->notification_campaign_id = $this->id;
            $msg->user_id = $user->id;
            $msg->title = $this->title;
            $msg->phone_number = Utils::prepare_phone_number($user->phone);
            $msg->email = $user->email;
            $msg->sms_body = $this->sms_body;
            $msg->short_description = $this->short_description;
            $msg->body = $this->body;
            $msg->image = $this->image;
            $msg->url = $this->url;
            $msg->type = $this->type;
            $msg->priority = $this->priority;
            $msg->status = $this->status;
            $msg->ready_to_send = 'Yes';
            $msg->send_notification = $this->send_notification;
            $msg->send_email = $this->send_email;
            $msg->send_sms = $this->send_sms;
            $msg->sheduled_at = $this->sheduled_at;
            $msg->email_sent = 'No';
            $msg->sms_sent = 'No';
            $msg->notification_seen = 'No';
            $msg->notification_seen_time = null;
            $msg->save();
        }
        $this->ready_to_send = 'Sent';
        $this->save();
    }

    //send_now
    public function send_now()
    {
        if ($this->ready_to_send != 'Yes') {
            //return;
        }
        //prepare messages
        $this->prepareSend();
        $messages = NotificationMessage::where('notification_campaign_id', $this->id)
            /* ->where('ready_to_send', 'Yes') */
            ->get();

        //die($messages);
        foreach ($messages as $message) {
            $message->send_now();
        }
    }
}
