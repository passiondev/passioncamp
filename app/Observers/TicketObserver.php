<?php

namespace App\Observers;

class TicketObserver
{
    public function canceled($ticket)
    {
        if ($ticket->has('waiver')) {
            $ticket->waiver->delete();
        }
    }

    public function deleted($ticket)
    {
        if ($ticket->has('waiver')) {
            $ticket->waiver->delete();
        }
    }
}
