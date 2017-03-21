<?php

namespace App\Http\Controllers\Account;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        auth()->user()->organization->load('orders.items', 'orders.tickets', 'orders.donations', 'orders.transactions');

        return view('account.dashboard')->withOrganization(auth()->user()->organization);
    }
}
