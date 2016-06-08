<?php

namespace App\Http\Controllers\Order;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $this->authorize('record-notes', $order);

        $this->validate($request, [
            'body' => 'required'
        ]);

        $note = $order->addNote($request->body);

        return redirect()->route('order.show', $order);
    }
}
