<?php

namespace App\Http\Controllers;

use App\Organization;

class OrganizationPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index(Organization $organization)
    {
        return view('admin.organization.payment.index')->withOrganization($organization);
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
                        'church' => $organization->church->name
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
        } else {
            $organization->addTransaction([
                'amount' => request('amount') * 100,
                'source' => request('type'),
                'identifier' => request('identifier'),
            ]);
        }

        return redirect()->action('OrganizationController@show', $organization)->with('success', 'Payment added.');
    }
}
