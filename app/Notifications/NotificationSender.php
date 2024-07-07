<?php

namespace App\Services;

use App\Models\NotificationMessage;
use App\Models\User;
use App\Models\Utils;

class NotificationSender
{
    public function sendNotification($phone, $message, $additionalData = [])
    {
        // Find user by phone number
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            throw new \Exception("User not found with the given phone number.");
        }

        if ($user && $user->id) {
            Utils::sendNotification2([
                'msg' => $message,
                'headings' => 'New Notification',
                'receiver' => $user->id,
                'type' => 'text',
            ]);
        }

        $msg = new NotificationMessage();
        $msg->user_id = $user->id;
        $msg->phone_number = Utils::prepare_phone_number($phone);
        $msg->sms_body = $message;

        // Set default values
        $msg->title = $additionalData['title'] ?? 'New Notification';
        $msg->short_description = $additionalData['short_description'] ?? $message;
        $msg->body = $additionalData['body'] ?? $message;
        $msg->image = $additionalData['image'] ?? null;
        $msg->url = $additionalData['url'] ?? null;
        $msg->type = $additionalData['type'] ?? 'default';
        $msg->priority = $additionalData['priority'] ?? 'normal';
        $msg->status = $additionalData['status'] ?? 'pending';
        $msg->ready_to_send = 'Yes';
        $msg->send_notification = $additionalData['send_notification'] ?? true;
        $msg->send_email = $additionalData['send_email'] ?? false;
        $msg->send_sms = $additionalData['send_sms'] ?? false;
        $msg->sheduled_at = $additionalData['scheduled_at'] ?? now();
        $msg->email_sent = 'No';
        $msg->sms_sent = 'No';
        $msg->notification_seen = 'No';
        $msg->notification_seen_time = null;

        // Set notification_campaign_id if provided
        if (isset($additionalData['notification_campaign_id'])) {
            $msg->notification_campaign_id = $additionalData['notification_campaign_id'];
        }

        $msg->save();

        return $msg;
    }
}
