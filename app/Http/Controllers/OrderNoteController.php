<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Order $order)
    {
        $this->authorize('edit', $order);

        $this->validate(request(), [
            'body' => 'required',
        ]);

        $order->addNote(request('body'));

        return redirect()->back()->withSuccess('Note added.');
    }
}
