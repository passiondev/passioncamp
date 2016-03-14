<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::search($request->search)->with('tickets', 'tickets.person')->paginate(5);

        return view('admin.order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('tickets', 'tickets.person', 'donations', 'items', 'transactions', 'transactions.transaction', 'organization');

        return view('admin.order.show', compact('order'));
    }
}
