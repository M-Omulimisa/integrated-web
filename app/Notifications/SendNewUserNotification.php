<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;

class SendNewUserNotification extends Notification
{
    use Queueable;

    /**
     * The user array.
     *
     * @var array
     */
    public $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $roles = json_encode($this->request->input('roles'));

        if (isset($this->request->organisation) && !is_null($this->request->organisation)) {
            $organisation = " @ ".$this->request->organisation;
        }

        return (new MailMessage)
                    ->subject(config('app.name').' Account')
                    ->greeting('Hello '.$this->request->name)
                    ->line('Your account has been created on '.config('app.name'))
                    ->line('Roles: '.$roles.($organisation ?? null))
                    ->line('Email: '.$this->request->email)
                    ->line('Password: '.$this->request->password)
                    ->line('Phone: '.$this->request->phone)
                    ->line('Click button below to get started')
                    ->action('Get Started', url('/'))
                    ->line('You are welcome!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
