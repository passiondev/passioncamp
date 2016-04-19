<?php

namespace App\Http\Controllers\Order;

use App\Order;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->authorize('owner', request()->order);
    }

    public function edit(Order $order)
    {
        $contact = $order->user->person;
        return view('order.contact.edit', compact('contact'))->withOrder($order);
    }

    public function update(Request $request, Order $order)
    {
        $contact = $order->user->person;

        $contact->update($request->only('first_name', 'last_name', 'email', 'phone'));

        return redirect()->route('order.show', $order);
    }
}
