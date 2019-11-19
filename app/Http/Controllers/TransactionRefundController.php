<?php

namespace App\Http\Controllers;

use Stripe;
use App\TransactionSplit;
use App\Http\Middleware\Authenticate;

class TransactionRefundController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function create(TransactionSplit $split)
    {
        request()->intended(url()->previous());

        $this->authorize('update', $split->order);

        return view('transaction-refund.create', [
            'transaction' => $split,
        ]);
    }

    public function store(TransactionSplit $split)
    {
        $this->authorize('update', $split->order);

        request()->validate([
            'amount' => 'required|integer|min:1',
        ]);

        try {
            $refund = Stripe\Refund::create([
                'charge' => $split->transaction->identifier,
                'amount' => request('amount') * 100,
            ], [
                'api_key' => config('services.stripe.secret'),
                'stripe_account' => $split->order->organization->setting('stripe_user_id'),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        $split->order->addTransaction([
            'source' => 'stripe',
            'identifier' => $refund->id,
            'amount' => $refund->amount * -1,
        ]);

        return redirect()->intended(action('OrderController@show', $split->order))
            ->withSucess('Refund added.');
    }
}
