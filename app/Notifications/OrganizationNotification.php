<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrganizationNotification extends Notification
{
    use Queueable;

    protected $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'subject' => $this->subject,
        ];
    }
}
