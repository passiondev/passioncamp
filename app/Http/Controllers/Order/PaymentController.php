<?php

namespace App\Http\Controllers\Order;

use App\Order;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        $this->authorize('owner', $order);

        return view('order.payment.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorize('owner', $order);

        $this->validate($request, [
            'stripeToken' => 'required',
            'amount' => 'required|numeric|min:1|max:'.$order->balance
        ]);

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $charge = \Stripe\Charge::create([
              'amount' => $request->amount * 100,
              'currency' => 'usd',
              'source' => $request->stripeToken,
              'description' => 'Passion Camp',
              'metadata' => ['order_id' => $order->id, 'email' => $order->user->person->email, 'name' => $order->user->person->name]
            ], ['stripe_account' => $order->organization->setting('stripe_user_id')]);
        } catch (\Exception $e) {
            return redirect()->route('order.show', $order)->withInput()->with('error', $e->getMessage());
        }

        // Add payment to order
        $order->addTransaction([
            'source' => 'stripe',
            'processor_transactionid' => $charge->id,
            'amount' => $charge->amount / 100,
            'card_type' => $charge->source->brand,
            'card_num' => $charge->source->last4,
        ]);

        return redirect()->route('order.show', $order);
    }
}
