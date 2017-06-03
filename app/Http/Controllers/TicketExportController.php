<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Exports\TicketExport;

class TicketExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        set_time_limit(0);

        $tickets = Ticket::forUser(auth()->user())
                   ->active()
                   ->with('person', 'order.user.person', 'order.organization.church', 'roomAssignment.room.hotel', 'waiver')
                   ->get();

        $includeAdditionalFields = auth()->user()->isSuperAdmin() || auth()->user()->organization->slug == 'pcc';

        (new TicketExport($tickets, $includeAdditionalFields))->download();
        die();
    }
}
