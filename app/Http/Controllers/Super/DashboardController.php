<?php

namespace App\Http\Controllers\Super;

use App\Organization;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $data = [
            'num_churches' => Organization::active()->count(),
            'num_tickets' => Organization::withTicketsSum()->get()->sum('tickets_sum'),
            'total_cost' => Organization::withCostSum()->get()->sum('cost_sum') / 100,
            'total_paid' => Organization::withPaidSum()->get()->sum('paid_sum') / 100,
            'stripe' => Organization::withPaidSum('stripe')->get()->sum('stripe_paid_sum') / 100,
            'other' => Organization::withPaidSum('other')->get()->sum('other_paid_sum') / 100,
        ];

        $data['balance'] = $data['total_cost'] - $data['total_paid'];

        return request()->wantsJson()
            ? response()->json($data)
            : view('admin.dashboard', compact('data'));
    }
}
