<?php

namespace App\Http\Controllers;

use App\Organization;
use App\TransactionSplit;

class OrganizationPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
        $this->middleware('can:view, organization');
        $this->authorizeResource(TransactionSplit::class, 'payment');
    }

    public function index(Organization $organization)
    {
        return view('admin.organization.payment.index', compact('organization'));
    }

    public function store(Organization $organization)
    {
        $this->validate(request(), [
            'type' => 'required',
            'amount' => 'required|integer|not_in:0',
            'identifier' => 'required_unless:type,credit',
            'stripeToken' => 'required_if:type,credit',
        ]);

        if (request('type') == 'credit') {
            try {
                $charge = \Stripe\Charge::create([
                    'amount' => request('amount') * 100,
                    'currency' => 'usd',
                    'source' => request('stripeToken'),
                    'description' => 'Passion Camp',
                    'statement_descriptor' => 'Passion Camp',
                    'metadata' => [
                        'church' => $organization->church->name,
                    ],
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
        } else {
            $organization->addTransaction([
                'amount' => request('amount') * 100,
                'source' => request('type'),
                'identifier' => request('identifier'),
            ]);
        }

        return redirect()
            ->action('OrganizationController@show', $organization)
            ->with('success', 'Payment added.');
    }

    public function edit(Organization $organization, TransactionSplit $payment)
    {
        return view('admin.organization.payment.edit', compact('organization', 'payment') + [
            'sources' => [
                'stripe' => 'Stripe',
                'other' => 'Check / Other',
            ],
        ]);
    }

    public function update(Organization $organization, TransactionSplit $payment)
    {
        request()->validate([
            'amount' => 'required|integer|not_in:0',
            'source' => 'required',
            'identifier' => 'required',
        ]);

        $payment->update([
            'amount' => request()->input('amount') * 100,
        ]);

        $payment->transaction->update([
            'amount' => request()->input('amount') * 100,
            'source' => request()->input('source'),
            'identifier' => request()->input('identifier'),
        ]);

        return redirect()
            ->action('OrganizationController@show', $organization)
            ->with('success', 'Payment updated.');
    }

    public function destroy(Organization $organization, TransactionSplit $payment)
    {
        $payment->delete();
        $payment->transaction->delete();

        $organization->addNote(
            sprintf('Deleted %s payment.', money_format('%.2n', $payment->amount / 100))
        );

        return redirect()
            ->action('OrganizationController@show', $organization)
            ->with('success', 'Payment deleted.');
    }
}
