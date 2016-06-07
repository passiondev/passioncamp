<?php

namespace App\Http\Controllers\Organization;

class DashboardController extends Controller
{
    protected function index()
    {
        $this->organization->load('orders', 'orders.items', 'orders.tickets', 'orders.donations', 'orders.transactions');

        return view('organization.dashboard.index')->withOrganization($this->organization);
    }
}
