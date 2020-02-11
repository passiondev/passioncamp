<?php

namespace App\Http\Controllers;

use App\Exports\TicketExport;
use App\Http\Middleware\Authenticate;
use App\Ticket;

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

        $includeAdditionalFields = auth()->user()->isSuperAdmin() || 'pcc' == auth()->user()->organization->slug;

        (new TicketExport($tickets, $includeAdditionalFields))->download();
        die();
    }
}
