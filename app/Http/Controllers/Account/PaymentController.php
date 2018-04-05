<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyUserIsChurchAdmin;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(VerifyUserIsChurchAdmin::class);
    }

    public function index()
    {
        return view('account.payment.index', [
            'balance' => auth()->user()->organization->balance,
            'transactions' => auth()->user()->organization->transactions,
        ]);
    }

    public function store()
    {
        $this->validate(request(), [
            'amount' => 'required|integer|min:1',
            'stripeToken' => 'required',
        ]);

        $organization = auth()->user()->organization;

        try {
            $charge = \Stripe\Charge::create([
                'amount' => request('amount') * 100,
                'currency' => 'usd',
                'source' => request('stripeToken'),
                'description' => 'Passion Camp',
                'statement_descriptor' => 'Passion Camp',
                'metadata' => [
                    'church' => $organization->church->name,
                    'email' => auth()->user()->person->email,
                    'name' => auth()->user()->person->name
                ]
            ], ['api_key' => config('services.stripe.secret')]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $organization->addTransaction([
            'source' => 'stripe',
            'identifier' => $charge->id,
            'amount' => $charge->amount,
            'cc_brand' => $charge->source->brand,
            'cc_last4' => $charge->source->last4,
        ]);

        return redirect()->action('Account\PaymentController@index')->withSuccess('Payment received.');
    }
}
