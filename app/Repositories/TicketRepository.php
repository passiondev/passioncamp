<?php

namespace App\Repositories;

use App\Ticket;

class TicketRepository
{
    public function forUser($user)
    {
        if ($user->is_super_admin) {
            return (new Ticket);
        }

        return Ticket::where('organization_id', $user->organization_id);
    }

    public function update(Ticket $ticket, $data)
    {
        $ticket->fill($data)->save();
        $ticket->person->fill($data)->save();
    }
}
