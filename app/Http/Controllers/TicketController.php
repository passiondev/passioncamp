<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TicketRepository;

class TicketController extends Controller
{
    protected $tickets;

    public function __construct(TicketRepository $tickets)
    {
        if (request()->ticket) {
            $this->authorize('owner', request()->ticket->order);
        }
        
        $this->tickets = $tickets;
    }

    public function index(Request $request)
    {
        $tickets = $this->tickets
                   ->forUser(Auth::user())
                   ->search($request->search)
                   ->with('person', 'order', 'organization')
                   ->paginate(15);

        if ($tickets->count() > 0 && ! $request->search && ! $request->page) {
            return redirect()->route('ticket.index', ['page' => $tickets->lastPage()]);
        }

        return view('ticket.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        $formData = array_merge(
            $ticket->getAttributes(), 
            $ticket->person->getAttributes(), 
            ['birthdate' => $ticket->person->birthdate ? $ticket->person->birthdate->format('m/d/Y') : '']
        );

        $ticket_price = $ticket->price;

        return view('ticket.edit', compact('formData', 'ticket_price'))->withTicket($ticket);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->tickets->update($ticket, $request->all());

        return redirect()->route('order.show', $ticket->order);
    }

    public function cancel(Ticket $ticket)
    {
        $ticket->cancel();

        return redirect()->route('order.show', $ticket->order);
    }

    public function delete(Ticket $ticket)
    {
        $order = $ticket->order;

        $ticket->delete();

        return redirect()->route('order.show', $order);
    }
}
