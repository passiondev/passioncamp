<?php

namespace App\Http\Controllers;

use App\Ticket;

class TicketAssignmentController extends Controller
{
    public function destroy(Ticket $ticket)
    {
        $ticket->rooms()->detach();
    }
}
