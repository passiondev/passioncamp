<?php

namespace App\Http\Controllers\Account;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $ticket = (new Ticket)
            ->setRelation('order', (new Order)->setRelation('organization', auth()->user()->organization));

        return view('account.ticket.create', compact('ticket'));
    }

    public function store()
    {
        $this->validate(request(), [
            'ticket.agegroup' => 'required',
            'ticket.first_name' => 'required',
            'ticket.last_name' => 'required',
            'ticket.gender' => 'required',
            'ticket.grade' => 'required_if:ticket.agegroup,student',
            'contact.name' => 'required',
            'contact.email' => 'required|email',
            'contact.phone' => 'required',
        ]);

        $order = auth()->user()->organization->orders()->create([]);

        $ticket = new Ticket(array_only(request('ticket'), ['agegroup']));
        $ticket->organization()->associate(auth()->user()->organization);
        $ticket->person()->associate(
            Person::create(request(['considerations']) + array_only(request('ticket'), ['first_name', 'last_name', 'gender', 'grade', 'allergies']))
        );

        $order->user()->associate(User::create([
            'person_id' => Person::create(array_only(request('contact'), ['name', 'email', 'phone']))->id
        ]));

        $order->tickets()->save($ticket);

        $order->save();

        return redirect()->action('TicketController@index', ['page' => Ticket::forUser(auth()->user())->paginate()->lastPage()])->withSuccess('Attendee added.');
    }
}
