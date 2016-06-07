<?php

namespace App\Http\Controllers\Order;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('super');
    }

    public function store(Request $request, Order $order)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $note = $order->addNote($request->body);

        return redirect()->route('order.show', $order);
    }
}
