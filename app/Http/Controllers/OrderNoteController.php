<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Order;
use Illuminate\Http\Request;

class OrderNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function store(Order $order)
    {
        $this->authorize('update', $order);

        request()->validate([
            'body' => 'required',
        ]);

        $order->addNote(request('body'));

        return redirect()->back()->withSuccess('Note added.');
    }
}
