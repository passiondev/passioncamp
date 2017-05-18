<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;
use App\Jobs\Waiver\RequestWaiverSignature;

class TicketWaiversController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $waiver = $ticket->createWaiver();

        return request()->expectsJson()
            ? response()->json($waiver, 201)
            : redirect()->back();
    }
}
