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
            abort_unless(data_get($request->user(), 'organization.slug') == 'pcc', 403);

            return $next($request);
        });
    }

    public function index()
    {
        $tickets = [];

        if (request('search')) {
            $tickets = Ticket::search(request('query'))
                ->where('organization_id', auth()->user()->organization_id)
                ->where('is_canceled', false)
                ->keys();

            $tickets->load('person', 'order.user.items', 'order.user.transactions', 'waiver');
        }

        $organization = auth()->user()->organization()
            ->withCount('students', 'leaders', 'checkedInStudents', 'checkedInLeaders', 'activeAttendees')
            ->first();

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

        $ticket->printWristband(session('printer.id'), auth()->user()->organization->slug);

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

            PrintWristbandJob::dispatch($ticket, session('printer.id'), auth()->user()->organization->slug);
        });

        return redirect()->action('CheckinController@index');
    }
}
