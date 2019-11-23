<?php

namespace App\Http\Controllers\Account;

use App\Order;
use App\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyUserIsChurchAdmin;
use App\Http\Requests\AccountTicketCreateRequest;
use App\Http\Middleware\VerifyTicketCanBeAddedToOrganization;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth',
            VerifyUserIsChurchAdmin::class,
            VerifyTicketCanBeAddedToOrganization::class,
        ]);
    }

    public function create()
    {
        $ticket = (new Ticket())
            ->order()->associate(
                (new Order())->organization()->associate(auth()->user()->organization)
            );

        return view('account.ticket.create', compact('ticket'));
    }

    public function store(AccountTicketCreateRequest $request)
    {
        $request->persist();

        return redirect()->route('tickets.index', ['page' => 'last'])->withSuccess('Attendee added.');
    }
}
