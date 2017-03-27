<?php

namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use Illuminate\Http\Request;

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

        $ticket->update(array_only(request('ticket'), ['agegroup']));
        $ticket->person->update(request(['considerations']) + array_only(request('ticket'), ['first_name', 'last_name', 'gender', 'grade', 'allergies']));

        if (request()->has('contact')) {
            try {
                $user = User::whereEmail(array_get(request('contact'), 'email'))->firstOrFail();

                $this->authorize('update', $user);

                $user->person->update(array_only(request('contact'), ['name', 'email', 'phone']));
            } catch (ModelNotFoundException $e) {
                $person->update(array_only(request('contact'), ['name', 'email', 'phone']));
                $person->user->update(array_only(request('contact'), ['email']));
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
