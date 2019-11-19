<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Middleware\Authenticate;
use App\Jobs\Ticket\PrintWristbandJob;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);

        $this->middleware(function ($request, $next) {
            abort_unless('pcc' == data_get($request->user(), 'organization.slug'), 403);

            return $next($request);
        });
    }

    public function index()
    {
        $tickets = [];

        if (request('search')) {
            $tickets = Ticket::search(request('search'))
                ->where('organization_id', auth()->user()->organization_id)
                ->where('is_canceled', 0)
                ->get();

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

    public function showRemaining()
    {
        $tickets = Ticket::forUser(auth()->user())
            ->whereNull('canceled_at')
            ->whereNull('checked_in_at')
            ->get();

        $tickets->load('person', 'order.user.items', 'order.user.transactions', 'waiver');

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
}
