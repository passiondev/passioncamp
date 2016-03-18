<?php

namespace App\Http\Controllers\Organization;

use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function create(Organization $organization)
    {
        return view('admin.organization.payment.create')->withOrganization($organization);
    }

    public function store(Request $request, Organization $organization)
    {
        $organization->addPayment($request->all());

        return redirect()->route('admin.organization.show', $organization)->with('success', 'Payment added.');
    }
}
