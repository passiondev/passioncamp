<?php

namespace App\Http\Controllers;

use App\Ticket;
use Facades\App\Contracts\Printing\Factory as Printer;

class CheckinController extends Controller
{
    public function index()
    {
        if (request('search')) {
            $keys = Ticket::search(request('search'))
                ->where('organization_id', auth()->user()->organization_id)
                ->keys();

            $tickets = Ticket::whereIn('id', $keys)
                ->where([
                    'canceled_at' => null,
                    'agegroup' => 'student',
                ])
                ->with('person', 'order.user.items', 'order.user.transactions', 'waiver')
                ->get();
        } else {
            $tickets = [];
        }

        return view('checkin.index', compact('tickets'));
    }

    public function create(Ticket $ticket)
    {
        $ticket->checkin();

        Printer::driver(data_get(auth()->user(), 'organization.slug'))->print(
            session('printer'),
            action('TicketWristbandsController@signedShow', $ticket->toRouteSignatureArray()),
            [
                'title' => $ticket->name,
                'source' => 'PCC Check In'
            ]
        );

        session()->flash('checked_in', $ticket);

        return redirect()->action('CheckinController@index');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->uncheckin();

        session()->flash('unchecked_in', $ticket);

        return redirect()->action('CheckinController@index');
    }

    // public function doCheckin(Request $request, Ticket $ticket)
    // {
    //     $ticket->checkin();

    //     // generate pdf and send to printer
    //     $pdf = new \HTML2PDF('L', [254, 25.4], 'en', true, 'UTF-8', [2*25.4,2*2.5,2.5,2.5]);
    //     $pdf->writeHTML(view('ticket.wristband', compact('ticket'))->render());
    //     $pdf->writeHTML(view('ticket.wristband', compact('ticket'))->render());

    //     $handler = new PrintJobHandler(CheckinPrintNodeClient::init());
    //     $handler->withPrinter($request->session()->get('printer'))->setTitle($ticket->id)->output($pdf);

    //     return redirect()->route('checkin.index')->withTicketId($ticket->id)->withTicketName($ticket->person->name);
    // }

    // public function undoCheckin(Request $request, Ticket $ticket)
    // {
    //     $ticket->is_checked_in = false;
    //     $ticket->save();

    //     return redirect()->route('checkin.index')->withUncheck(true)->withTicketName($ticket->person->name);
    // }
}
