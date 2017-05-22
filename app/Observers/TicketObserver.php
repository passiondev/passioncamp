<?php

namespace App\Observers;

class TicketObserver
{
    public function canceled($ticket)
    {
        if ($ticket->waivers()->count()) {
            $ticket->waiver->delete();
        }
    }

    public function deleted($ticket)
    {
        if ($ticket->waivers()->count()) {
            $ticket->waiver->delete();
        }
    }
}
