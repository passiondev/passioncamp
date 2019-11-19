<?php

namespace App\Http\Controllers;

use App\Order;
use App\Exports\OrderExport;
use App\Http\Middleware\Authenticate;

class OrderExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function store()
    {
        $orders = Order::forUser()
            ->withCount('activeTickets')
            ->with('donations', 'items', 'transactions', 'user.items', 'user.transactions')
            ->get();

        (new OrderExport($orders))->download();
        die();
    }
}
