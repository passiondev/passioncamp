<?php

namespace App\Http\Controllers\Account;

class DashboardController extends BaseController
{
    public function index()
    {
        auth()->user()->organization->load('orders.items', 'orders.tickets', 'orders.donations', 'orders.transactions');

        return view('organization.show')->withOrganization(auth()->user()->organization);
    }
}
