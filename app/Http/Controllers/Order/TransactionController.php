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

    public function store(Request $request, Order $order)
    {
        $this->authorize('owner', $order);

        $transaction = null;

        if ($order->organization->can_make_stripe_payments && $request->type == 'credit') {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $charge = \Stripe\Charge::create([
                  'amount' => $request->amount * 100,
                  'currency' => 'usd',
                  'source' => $request->stripeToken
                ], ['stripe_account' => $order->organization->setting('stripe_user_id')]);
            } catch (\Exception $e) {
                return redirect()->back()->withError($e->getMessage());
            }

            $transaction = new Transaction;
            $transaction->amount = $request->amount;
            $transaction->processor_transactionid = $charge->id;
            $transaction->card_type = $charge->source->brand;
            $transaction->card_num = $charge->source->last4;
            $transaction->type = ucwords($request->type);
            $transaction->source = 'stripe';
            $transaction->save();
        }

        if (is_null($transaction)) {
            $transaction = new Transaction;
            $transaction->amount = $request->amount;
            $transaction->processor_transactionid = $request->transaction_id;
            $transaction->type = ucwords($request->type);
            $transaction->save();
        }

        $split = new TransactionSplit;
        $split->transaction()->associate($transaction);
        $split->order()->associate($order);
        $split->amount = $request->amount;
        $split->save();

        return redirect()->route('order.show', $order);
    }
}
