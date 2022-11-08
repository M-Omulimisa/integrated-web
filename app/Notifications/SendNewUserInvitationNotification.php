<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use App\Models\UserManagement\UserInvitation;

class SendNewUserInvitationNotification extends Notification
{
    use Queueable;

    /**
     * The user array.
     *
     * @var array
     */
    public $invite;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserInvitation $invite)
    {
        $this->invite = $invite;
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
        // $role = Role::whereId($this->invite->role_id)->first();
        $rejectUrl = url('account/reject/'.$this->invite->id.'/'.$this->invite->token);
        $acceptUrl = url('account/accept/'.$this->invite->id.'/'.$this->invite->token);
        $institution = !is_null($this->invite->institution_id) ? $this->invite->institution->participating_institution_id : $this->invite->institution_name;
        return (new MailMessage)
                    ->subject(config('app.name').' Invitation')
                    ->greeting('Hello '.$this->invite->name)
                    ->line('You have been invited to '.config('app.name').' portal to create an account as '.$institution.'\'s '.$this->invite->role->name.'')
                    ->action('Accept Invitation', $acceptUrl)
                    ->line('This invitation expires in 48 Hours!')
                    ->markdown(
                        'vendor.notifications.invite', 
                        [
                            'rejectText'            => 'Reject Invitation',
                            'rejectUrl'             => $rejectUrl,
                            'displayableRejectUrl'  => str_replace(['mailto:', 'tel:'], '', $rejectUrl ?? ''),
                        ]
                    );
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
