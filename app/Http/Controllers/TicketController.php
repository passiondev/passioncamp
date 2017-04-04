<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

        $ticket->update(
            array_only(request('ticket'), ['agegroup', 'squad'])
            + [
                'ticket_data' => request('ticket_data'),
                'price' => array_has(request('ticket'), 'price') ? array_get(request('ticket'), 'price') * 100 : null
            ]
        );
        $ticket->person->update(request(['considerations']) + array_only(request('ticket'), ['first_name', 'last_name', 'gender', 'grade', 'allergies', 'email', 'phone', 'birthdate']));

        if (request()->has('contact')) {
            try {
                $user = User::whereEmail(array_get(request('contact'), 'email'))->firstOrFail();

                $this->authorize('update', $user);

                $user->person->update(array_only(request('contact'), ['name', 'email', 'phone']));
            } catch (ModelNotFoundException $e) {
                $ticket->order->user->person->update(array_only(request('contact'), ['name', 'email', 'phone']));
                $ticket->order->user->person->user->update(array_only(request('contact'), ['email']));
            } catch (\Exception $e) {
                return redirect()->back()->withError($e->getMessage());
            }
        }

        return redirect()->intended(action('TicketController@index'))->withSuccess('Attendee updated.');
    }

    public function cancel(Ticket $ticket)
    {
        $this->authorize('cancel', $ticket);

        $ticket->cancel(auth()->user());

        return redirect()->action('TicketController@index')->withSuccess('Ticket canceled');
    }

    public function delete(Ticket $ticket)
    {
        $this->authorize($ticket);

        $ticket->delete();

        return redirect()->action('TicketController@index')->withSuccess('Ticket deleted');
    }
}
