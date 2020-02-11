<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Ticket;

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
