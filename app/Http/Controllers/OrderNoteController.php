<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderNoteController extends Controller
{
    public function store(Order $order)
    {
        $this->authorize('edit', $order);

        $this->validate(request(), [
            'body' => 'required',
        ]);

        $order->addNote(request('body'));

        return redirect()->action('OrderController@show', $order)->withSuccess('Note added.');
    }
}
