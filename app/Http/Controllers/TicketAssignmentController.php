<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Middleware\Authenticate;

class TicketAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->rooms()->detach();
    }
}
