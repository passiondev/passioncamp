<?php

namespace App\Http\Controllers\Account;

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountTicketCreateRequest;
use App\Http\Middleware\VerifyTicketCanBeAddedToOrganization;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth',
            VerifyTicketCanBeAddedToOrganization::class
        ]);
    }

    public function create()
    {
        $ticket = (new Ticket)
            ->order()->associate(
                (new Order)->organization()->associate(auth()->user()->organization)
            );

        return view('account.ticket.create', compact('ticket'));
    }

    public function store(AccountTicketCreateRequest $request)
    {
        $request->persist();

        return redirect()->route('tickets.index', ['page' => 'last'])->withSuccess('Attendee added.');
    }
}
