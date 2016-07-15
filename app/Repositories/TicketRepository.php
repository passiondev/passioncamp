<?php

namespace App\Repositories;

use App\Ticket;

class TicketRepository
{
    public function forUser($user)
    {
        \Log::warning('TicketRepository@forUser deprecated');
        return Ticket::forUser($user);
    }

    public function make(array $data, Ticket $ticket = null)
    {
        $ticket = $ticket ?: new Ticket;

        $ticket_data = array_only($data, [
            'school',
            'shirtsize',
            'roommate_requested',
            'leader',
            'bus',
        ]);

        $ticket->fill($data)->setAttribute('ticket_data', $ticket_data);

        return $ticket;
    }

    public function update(Ticket $ticket, array $data)
    {
        $this->make($data, $ticket)->save();
        $ticket->person->fill($data)->save();
    }
}
