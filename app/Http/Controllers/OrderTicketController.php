<?php

namespace App\Http\Controllers;

use App\Order;
use App\Ticket;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifyTicketCanBeAddedToOrganization;

class OrderTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            Authenticate::class,
            VerifyTicketCanBeAddedToOrganization::class,
        ]);
    }

    public function create(Order $order)
    {
        $this->authorize('update', $order);

        request()->intended(url()->previous());

        $ticket = (new Ticket)->order()->associate($order);

        return view('order-ticket.create', compact('ticket'));
    }

    public function store(Order $order)
    {
        $this->authorize('update', $order);

        request()->validate([
            'ticket.agegroup' => 'required',
            'ticket.first_name' => 'required',
            'ticket.last_name' => 'required',
            'ticket.gender' => 'required',
            'ticket.grade' => 'required_if:ticket.agegroup,student',
        ]);

        $ticket = new Ticket([
            'agegroup' => request()->input('ticket.agegroup'),
            'person' => [
                'considerations' => request()->input('considerations'),
                'first_name' => request()->input('ticket.first_name'),
                'last_name' => request()->input('ticket.last_name'),
                'gender' => request()->input('ticket.gender'),
                'grade' => request()->input('ticket.grade'),
                'allergies' => request()->input('ticket.allergies'),
            ],
        ]);

        $ticket->organization()->associate(auth()->user()->organization);

        $order->tickets()->save($ticket);

        return redirect()->intended(action('OrderController@show', $order))->withSuccess('Attendee added.');
    }
}
