<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;

class OrderTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function create(Order $order)
    {
        $this->authorize('update', $order);

        return view('order-transaction.create', compact('order'));
    }

    public function store(Order $order)
    {
        $this->authorize('update', $order);

        $this->validate(request(), [
            'type' => 'required',
            'amount' => 'required|integer|not_in:0',
            'identifier' => 'required_unless:type,credit',
            'stripeToken' => 'required_if:type,credit',
        ]);

        if ('credit' == request('type')) {
            try {
                $charge = \Stripe\Charge::create(
                    [
                        'amount' => request('amount') * 100,
                        'currency' => 'usd',
                        'source' => request('stripeToken'),
                        'description' => 'Passion Camp',
                        'statement_descriptor' => 'Passion Camp',
                        'metadata' => [
                            'order_id' => $order->id,
                            'name' => $order->user->person->name,
                            'email' => $order->user->person->email,
                        ],
                    ],
                    [
                        'api_key' => config('settings.stripe.secret'),
                        'stripe_account' => 'acct_16y17LI1xefq0oDy',
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }

            $order->addTransaction([
                'source' => 'stripe',
                'identifier' => $charge->id,
                'amount' => $charge->amount,
                'cc_brand' => $charge->source->brand,
                'cc_last4' => $charge->source->last4,
            ]);
        } else {
            $order->addTransaction([
                'amount' => request('amount') * 100,
                'source' => request('type'),
                'identifier' => request('identifier'),
            ]);
        }

        return redirect()->action('OrderController@show', $order)->with('success', 'Transaction added.');
    }
}
