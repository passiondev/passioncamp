<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Requests;
use App\PrintJobHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

class CheckinController extends Controller
{
    public function index(UrlGenerator $generator, Request $request)
    {
        \Session::put('url.intended', $generator->full());
        
        $tickets = $request->search ? Ticket::forUser()
                   ->search($request->search)
                   ->with('person', 'order.transactions', 'order.items', 'waiver')
                   ->get() : [];

        return view('checkin.index', compact('tickets'));
    }

    public function doCheckin(Request $request, Ticket $ticket)
    {
        $ticket->checkin();

        // generate pdf and send to printer
        $pdf = new \HTML2PDF('L', [254, 25.4], 'en', true, 'UTF-8', [2*25.4,2*2.5,2.5,2.5]);
        $pdf->writeHTML(view('ticket.wristband', compact('ticket'))->render());
        $pdf->writeHTML(view('ticket.wristband', compact('ticket'))->render());

        $print_handler = (new PrintJobHandler)
            ->withPrinter($request->session()->get('printer'))
            ->setTitle($ticket->id)
            ->output($pdf);

        return redirect()->route('checkin.index')->withTicketId($ticket->id)->withTicketName($ticket->person->name);
    }

    public function undoCheckin(Request $request, Ticket $ticket)
    {
        $ticket->is_checked_in = false;
        $ticket->save();

        return redirect()->route('checkin.index')->withUncheck(true)->withTicketName($ticket->person->name);
    }
}
