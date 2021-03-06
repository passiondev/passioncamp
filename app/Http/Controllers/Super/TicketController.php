<?php

namespace App\Http\Controllers\Super;

use App\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Routing\UrlGenerator;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
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

    public function update(UrlGenerator $generator, Ticket $ticket)
    {
        $this->authorize('owner', $ticket);

        $this->validate(request(), [
            'agegroup' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'grade' => 'required_if:agegroup,student',
        ]);

        $ticket->person->update(request([
            'first_name', 'last_name', 'email', 'phone',
            'birthdate', 'gender', 'grade', 'allergies',
        ]));

        $ticket->fill(
            request(['agegroup', 'squad', 'is_checked_in'])
        )->fill([
            'ticket_data' => request([
                'shirtsize', 'school', 'roommate_requested',
                'travel_plans', 'leader', 'bus', 'pcc_waiver'
            ]),
            'price' => request('price') * 100,
        ])->save();

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
