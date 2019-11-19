<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Exports\TicketExport;
use App\Http\Middleware\Authenticate;

class TicketExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function store()
    {
        set_time_limit(0);

        $tickets = Ticket::forUser(auth()->user())
                   ->active()
                   ->with('person', 'order.user.person', 'order.organization.church', 'roomAssignment.room.hotel', 'waiver')
                   ->get();

        if (! $tickets->count()) {
            return redirect()->back();
        }

        $includeAdditionalFields = auth()->user()->isSuperAdmin() || auth()->user()->organization->slug == 'pcc';

        (new TicketExport($tickets, $includeAdditionalFields))->download();
        die();
    }
}
