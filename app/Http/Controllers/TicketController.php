<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TicketRepository;

class TicketController extends Controller
{
    protected $tickets;

    public function __construct(TicketRepository $tickets)
    {
        $this->middleware('admin')->except('edit', 'update');

        $this->tickets = $tickets;
    }

    public function index(Request $request)
    {
        $tickets = Ticket::forUser()
                   ->search($request->search)
                   ->with('person', 'order', 'organization.church', 'organization.settings', 'waiver')
                   ->get();

        // if ($tickets->count() > 0 && ! $request->search && ! $request->page) {
        //     return redirect()->route('ticket.index', ['page' => $tickets->lastPage()]);
        // }

        return view('ticket.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        return redirect()->route('order.show', $ticket->order);
    }

    public function edit(UrlGenerator $generator, Ticket $ticket)
    {
        $this->authorize('owner', $ticket);

        $formData = array_merge(
            $ticket->getAttributes(),
            $ticket->person->getAttributes(),
            ['birthdate' => $ticket->person->birthdate ? $ticket->person->birthdate->format('m/d/Y') : ''],
            is_array($ticket->ticket_data()) ? $ticket->ticket_data() : [],
            ['shirtsize' => $ticket->shirt_size]
        );

        $ticket_price = $ticket->price;

        return view('ticket.edit', compact('formData', 'ticket_price'))->withTicket($ticket);
    }

    public function update(Request $request, UrlGenerator $generator, Ticket $ticket)
    {
        $this->authorize('owner', $ticket);

        $this->validate($request, [
            'agegroup' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'grade' => 'required_if:agegroup,student',
        ]);

        $this->tickets->update($ticket, $request->all());

        return redirect()->intended($generator->previous());
    }

    public function cancel(Ticket $ticket)
    {
        $this->authorize('owner', $ticket);

        $ticket->cancel();

        return redirect()->route('order.show', $ticket->order);
    }

    public function delete(Ticket $ticket)
    {
        $this->authorize('owner', $ticket);

        $order = $ticket->order;

        $ticket->delete();

        return redirect()->route('order.show', $order);
    }
}
