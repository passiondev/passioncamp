<?php

namespace App\Http\Controllers\Order;

use App\Order;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function create(Order $order)
    {
        $gradeOptions = [];
        foreach (range(6,12) as $grade) {
            $gradeOptions[$grade] = number_ordinal($grade);
        }

        return view('order.ticket.create', compact('gradeOptions'))->withOrder($order);
    }

    public function store(Request $request, Order $order)
    {
        $order->addTicket($request->all());

        return redirect()->route('order.show', $order)->with('success', 'Ticket created');
    }
}
