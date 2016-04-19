<?php

namespace App\Http\Controllers\Organization;

use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Organization $organization = null)
    {
        if (is_null($organization->id)) {
            $organization = Auth::user()->organization;
        }

        return view('admin.organization.payment.create')->withOrganization($organization);
    }

    public function store(Request $request, Organization $organization)
    {
        $account = false;

        if (is_null($organization->id)) {
            $account = true;
            $organization = Auth::user()->organization;
        }

        try {
            $organization->addPayment($request->all());
        } catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        if ($account) {
            return redirect()->route('account.dashboard')->with('success', 'Payment added.');
        }

        return redirect()->route('admin.organization.show', $organization)->with('success', 'Payment added.');
    }
}
