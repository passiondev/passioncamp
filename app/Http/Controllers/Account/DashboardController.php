<?php

namespace App\Http\Controllers\Account;

class DashboardController extends BaseController
{
    public function index()
    {
        $this->organization->load('orders.items', 'orders.tickets', 'orders.donations', 'orders.transactions');

        return view('organization.show')->withOrganization($this->organization);
    }
}