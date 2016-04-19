<?php

namespace App\Http\Controllers\Order;

use App\Order;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->authorize('owner', request()->order);
    }

    public function create(Order $order)
    {
        $ticket_price = $order->organization->setting('ticket_price') ? $order->organization->setting('ticket_price') : 0;

        return view('order.ticket.create', compact('ticket_price'))->withOrder($order);
    }

    public function store(Request $request, Order $order)
    {
        $order->addTicket($request->all());

        return redirect()->route('order.show', $order)->with('success', 'Ticket created');
    }
}
