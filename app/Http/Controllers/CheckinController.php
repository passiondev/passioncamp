<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Requests;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index(Request $request)
    {
        \Session::put('url.intended', route('checkin.index'));
        
        $tickets = $request->search ? Ticket::forUser()
                   ->search($request->search)
                   ->with('person', 'order', 'waiver')
                   ->get() : [];

        return view('checkin.index', compact('tickets'));
    }

    public function doCheckin(Ticket $ticket)
    {
        $ticket->checkin();

        return redirect()->route('checkin.index')->withTicketId($ticket->id)->withTicketName($ticket->person->name);
    }

    public function undoCheckin(Ticket $ticket)
    {
        $ticket->is_checked_in = false;
        $ticket->save();

        return redirect()->route('checkin.index')->withUncheck(true)->withTicketName($ticket->person->name);
    }
}
