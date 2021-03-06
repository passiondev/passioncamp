<?php

namespace App\Http\Controllers;

use App\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Order $order)
    {
        $this->authorize($order);

        $order->load('tickets.person', 'user.transactions', 'user.tickets');

        return view('order.show', compact('order'));
    }
}
