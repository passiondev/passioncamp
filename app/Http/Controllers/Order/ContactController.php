<?php

namespace App\Http\Controllers\Order;

use App\User;
use App\Order;
use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function create(Order $order)
    {
        $this->authorize('owner', $order);

        return view('order.contact.create')->withOrder($order);
    }

    public function store(Request $request, Order $order)
    {
        $this->authorize('owner', $order);

        $person = Person::create($request->only('first_name', 'last_name', 'email', 'phone'));
        
        $user = new User;
        $user->person()->associate($person);
        $user->save();

        $order->user()->associate($user);
        $order->save();

        return redirect()->route('order.show', $order);
    }

    public function edit(Order $order)
    {
        $this->authorize('owner', $order);

        $contact = $order->user->person;
        return view('order.contact.edit', compact('contact'))->withOrder($order);
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('owner', $order);

        $contact = $order->user->person;

        $contact->update($request->only('first_name', 'last_name', 'email', 'phone'));

        return redirect()->route('order.show', $order);
    }
}
