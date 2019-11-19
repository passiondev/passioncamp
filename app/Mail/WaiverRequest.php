<?php

namespace App\Mail;

use App\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WaiverRequest extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Passion Students Waiver for '.$this->ticket->person->name)
            ->markdown('emails.ticket.waiver')
            ->with([
                'student' => $this->ticket->person->name,
                'waiverLink' => $this->ticket->waiverLink(),
            ]);
    }
}
