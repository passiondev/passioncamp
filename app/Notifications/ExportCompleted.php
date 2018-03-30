<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ExportCompleted extends Notification
{
    use Queueable;

	protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The rooming list version export is now complete and has been attached to this email.')
                    ->attach(Storage::disk('exports')->path($this->version->file_name));

    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'version_id' => $this->version->id,
        ]);
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
