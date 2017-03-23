<?php

namespace App\Http\Controllers;

use App\Order;
use App\Person;
use App\Ticket;
use Illuminate\Http\Request;

class OrderTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('hasEnoughTickets');
    }

    public function create(Order $order)
    {
        $this->authorize('edit', $order);

        request()->intended(url()->previous());

        $ticket = (new Ticket)->setRelation('order', $order);

        return view('order-ticket.create', compact('ticket'));
    }

    public function store(Order $order)
    {
        $this->authorize('edit', $order);

        $this->validate(request(), [
            'ticket.agegroup' => 'required',
            'ticket.first_name' => 'required',
            'ticket.last_name' => 'required',
            'ticket.gender' => 'required',
            'ticket.grade' => 'required_if:ticket.agegroup,student',
        ]);

        $ticket = new Ticket(array_only(request('ticket'), ['agegroup']));
        $ticket->organization()->associate(auth()->user()->organization);
        $ticket->person()->associate(
            Person::create(request(['considerations']) + array_only(request('ticket'), ['first_name', 'last_name', 'gender', 'grade', 'allergies']))
        );

        $order->tickets()->save($ticket);

        return redirect()->intended(action('OrderController@show', $order))->withSuccess('Attendee added.');
    }
}
