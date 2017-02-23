<?php

namespace App\Http\Controllers\Order;

use App\Order;
use App\Transaction;
use App\Http\Requests;
use App\TransactionSplit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function create(Order $order)
    {
        $this->authorize('owner', $order);

        $payment_methods = ['credit' => 'Credit', 'check' => 'Check', 'other' => 'Other'];

        if (! $order->organization->can_make_stripe_payments) {
            unset($payment_methods['credit']);
        }

        return view('order.transaction.create', compact('payment_methods'))->withOrder($order);
    }

    public function store(Order $order)
    {
        $this->authorize('owner', $order);

        if ($order->organization->can_make_stripe_payments && request('type') == 'credit') {
            try {
                $charge = \Stripe\Charge::create(
                    [
                        'amount' => request('amount') * 100,
                        'currency' => 'usd',
                        'source' => request('stripeToken'),
                        'description' => 'Passion Camp',
                        'statement_descriptor' => 'PCC SMMR CMP',
                        'metadata' => [
                            'order_id' => $order->id,
                            'email' => $order->user->person->email,
                            'name' => $order->user->person->name
                        ]
                    ],
                    [
                        'api_key' => config('services.stripe.secret'),
                        'stripe_account' => $order->organization->setting('stripe_user_id'),
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withError($e->getMessage());
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
                'source' => request('type'),
                'identifier' => request('transaction_id'),
                'amount' => request('amount') * 100,
            ]);
        }

        return redirect()->route('order.show', $order);
    }
}
