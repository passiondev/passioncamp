<?php

namespace App\Http\Controllers\Super;

use App\Organization;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function __invoke()
    {
        $data = [
            'num_churches' => Organization::count(),
            'num_tickets' => Organization::join('order_items', function ($join) {
                $join->on('organizations.id', '=', 'organization_id')
                     ->where('org_type', 'ticket')
                     ->whereNull('order_items.canceled_at')
                     ->whereNull('order_items.deleted_at');
            })->select(\DB::raw('SUM(quantity) AS computed_num_tickets'))->first()->computed_num_tickets,
            'total_cost' => Organization::totalCost() / 100,
            'total_paid' => Organization::totalPaid() / 100,
            'stripe' => Organization::totalPaid('stripe') / 100,
            'other' => Organization::totalPaid('other') / 100,
        ];

        $data['balance'] = $data['total_cost'] - $data['total_paid'];

        return view('admin.dashboard', compact('data'));
    }
}
