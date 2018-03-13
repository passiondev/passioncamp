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
        $this->middleware('hasEnoughTickets');
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

        if (auth()->user()->organization->slug == 'pcc') {
            $user = User::firstOrCreate([
                'email' => request()->input('contact.email')
            ]);
        } else {
            $user = User::create([]);
        }

        if ($user->wasRecentlyCreated || ! $user->person) {
            $user->person()->associate(
                Person::create(array_only(request('contact'), ['name', 'email', 'phone']))
            )->save();
        } else {
            $user->person->update(array_only(request('contact'), ['name', 'email', 'phone']));
        }

        $order = $user->orders()->create([
            'organization_id' => auth()->user()->organization_id
        ]);

        $order->tickets()->save(
            (new Ticket(
                array_only(request('ticket'), ['agegroup'])
            ))->person()->associate(
                Person::create(
                    request(['considerations'])
                    + array_only(request('ticket'), [
                        'first_name',
                        'last_name',
                        'gender',
                        'grade',
                        'allergies'
                    ])
                )
            )
        );

        return redirect()->action('TicketController@index', ['page' => Ticket::forUser(auth()->user())->paginate()->lastPage()])->withSuccess('Attendee added.');
    }
}
