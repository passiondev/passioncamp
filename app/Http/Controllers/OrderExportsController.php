<?php

namespace App\Http\Controllers;

use App\Order;
use App\Exports\OrderExport;
use Illuminate\Http\Request;

class OrderExportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        $orders = Order::forUser(auth()->user())->withCount('activeTickets')->with('donations', 'items', 'transactions', 'user.items', 'user.transactions')->get();

        (new OrderExport($orders))->download();
        die();
    }
}
