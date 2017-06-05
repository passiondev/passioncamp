<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Jobs\Ticket\PrintWristbandJob;
use Facades\App\Contracts\Printing\Factory as Printer;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            abort_unless(data_get($request->user(), 'organization.slug') == 'pcc', 401);

            return $next($request);
        });
    }

    public function index()
    {
        if (request('search')) {
            $keys = Ticket::search(request('search'))
                ->where('organization_id', auth()->user()->organization_id)
                ->keys();

            $tickets = Ticket::whereIn('id', $keys)
                ->where([
                    'canceled_at' => null,
                ])
                ->with('person', 'order.user.items', 'order.user.transactions', 'waiver')
                ->get();
        } else {
            $tickets = [];
        }

        $organization = auth()->user()->organization()->withCount('students', 'leaders', 'checkedInStudents', 'checkedInLeaders', 'activeAttendees')->first();

        return view('checkin.index', [
            'tickets' => $tickets,
            'students_progress' => round($organization->checked_in_students_count / $organization->students_count, 4),
            'leaders_progress' => round($organization->checked_in_leaders_count / $organization->leaders_count, 4),
            'students_percentage' => round($organization->students_count / $organization->active_attendees_count, 4),
            'leaders_percentage' => round($organization->leaders_count / $organization->active_attendees_count, 4),
            'students_remaining' => $organization->students_count - $organization->checked_in_students_count,
            'leaders_remaining' => $organization->leaders_count - $organization->checked_in_leaders_count,
        ]);
    }

    public function create(Ticket $ticket)
    {
        $ticket->checkin();

        $ticket->printWristband(session('printer.id'), data_get(auth()->user(), 'organization.slug'));

        session()->flash('checked_in', $ticket);

        return redirect()->action('CheckinController@index');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->uncheckin();

        session()->flash('unchecked_in', $ticket);

        return redirect()->action('CheckinController@index');
    }

    public function allLeaders()
    {
        auth()->user()->organization->leaders->each(function ($ticket) {
            $ticket->checkin();
            dispatch(new PrintWristbandJob($ticket, session('printer.id'), data_get(auth()->user(), 'organization.slug')));
        });

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
