<?php

namespace App\Http\Controllers;

use App\Order;
use App\Http\Middleware\Authenticate;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function show(Order $order)
    {
        $this->authorize($order);

        $order->load('tickets.person', 'user.transactions', 'user.tickets');

        return view('order.show', compact('order'));
    }
}
