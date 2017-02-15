<?php

namespace App\Listeners\Waivers\EmailBounced;

use App\Events\Waivers\EmailBounced;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  EmailBounced  $event
     * @return void
     */
    public function handle(EmailBounced $event)
    {
        $content = "Waiver email bounced for ticket #{$event->waiver->ticket->id}.\r\n\r\n".route('ticket.show', $event->waiver->ticket);
        \Mail::raw($content, function ($m) {
            $m->to('passioncamp@268generation.com');
            $m->subject('Waiver Email Bounced');
        });
    }
}
