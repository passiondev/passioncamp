<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.magic');
    }

    public function index()
    {
        $transactions = auth()->user()->transactions()->latest()->get();
        $balance = auth()->user()->balance;

        return view('user.payments.index', compact('transactions', 'balance'));
    }

    public function store()
    {
        request()->validate([
            'amount' => 'required|integer|min:1',
            'stripeToken' => 'required',
        ]);

        $order = auth()->user()->orders()->create([
            'organization_id' => 2,
        ]);

        try {
            $charge = \Stripe\Charge::create([
                'amount' => request('amount') * 100,
                'currency' => 'usd',
                'source' => request('stripeToken'),
                'description' => 'Passion Camp',
                'statement_descriptor' => 'Passion Camp',
                'metadata' => [
                    'email' => auth()->user()->person->email,
                    'name' => auth()->user()->person->name,
                ],
            ], [
                'api_key' => config('settings.stripe.secret'),
                'stripe_account' => 'acct_16y17LI1xefq0oDy',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }

        $order->addTransaction([
            'source' => 'stripe',
            'identifier' => $charge->id,
            'amount' => $charge->amount,
            'cc_brand' => $charge->source->brand,
            'cc_last4' => $charge->source->last4,
        ]);

        return redirect()->route('user.payments.index')
            ->withSuccess('Payment received.');
    }
}
