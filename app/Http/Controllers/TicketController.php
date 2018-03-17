<?php

namespace App\Http\Controllers;

use App\Ticket;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tickets = Ticket::forUser(auth()->user())
            ->with('person', 'order.organization.church')
            ->paginate();

        if (request()->query('page') == 'last') {
            return redirect()->route('tickets.index', ['page' => $tickets->lastPage()]);
        }

        return view('ticket.index', compact('tickets'));
    }

    public function search()
    {
        // $tickets = Ticket::search(request('query'))->where(function ($q) {
        //     return auth()->user()->isSuperAdmin() ?: $q->where('organization_id');
        // })->paginate();

        $tickets = Ticket::search(request('query'));

        if (! auth()->user()->isSuperAdmin()) {
            $tickets->where('organization_id', auth()->user()->organization_id);
        }

        $tickets = $tickets->paginate();

        $tickets->load('person', 'order.organization.church');

        return view('ticket.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        request()->intended(url()->previous());

        $this->authorize($ticket);

        $ticket->load('person');

        return view('ticket.edit', compact('ticket'));
    }

    public function update(Ticket $ticket)
    {
        $this->authorize($ticket);

        $this->validate(request(), [
            'ticket.agegroup' => 'required',
            'ticket.first_name' => 'required',
            'ticket.last_name' => 'required',
            'ticket.gender' => 'required',
            'ticket.grade' => 'required_if:ticket.agegroup,student',
            'contact.name' => 'sometimes|required',
            'contact.email' => 'sometimes|required|email',
            'contact.phone' => 'sometimes|required',
        ]);

        $ticket->update([
            'agegroup' => request()->input('ticket.agegroup'),
            'squad' => request()->input('ticket.squad'),
            'ticket_data' => request('ticket_data'),
            'price' => request()->has('ticket.price') ? request()->input('ticket.price') * 100 : null,
            'person' => [
                'considerations' => request('considerations'),
                'first_name' => request()->input('ticket.first_name'),
                'last_name' => request()->input('ticket.last_name'),
                'gender' => request()->input('ticket.gender'),
                'grade' => request()->input('ticket.grade'),
                'allergies' => request()->input('ticket.allergies'),
                'email' => request()->input('ticket.email'),
                'phone' => request()->input('ticket.phone'),
                'birthdate' => request()->input('ticket.birthdate'),
            ]
        ]);

        if (request()->has('contact')) {
            $ticket->order->user->person->update([
                'name' => request()->input('contact.name'),
                'email' => request()->input('contact.email'),
                'phone' => request()->input('contact.phone'),
            ]);
        }

        return redirect()->intended(route('tickets.index'))->withSuccess('Attendee updated.');
    }

    public function cancel(Ticket $ticket)
    {
        $this->authorize('cancel', $ticket);

        $ticket->cancel();

        return redirect()->route('tickets.index')->withSuccess('Ticket canceled');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize($ticket);

        $ticket->delete();

        return redirect()->route('tickets.index')->withSuccess('Ticket deleted');
    }
}
