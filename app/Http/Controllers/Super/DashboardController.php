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
            'num_tickets' => Organization::all()->sum('tickets_sum'),
            'total_cost' => Organization::totalCost() / 100,
            'total_paid' => Organization::totalPaid() / 100,
            'stripe' => Organization::totalPaid('stripe') / 100,
            'other' => Organization::totalPaid('other') / 100,
        ];

        $data['balance'] = $data['total_cost'] - $data['total_paid'];

        return request()->wantsJson() ? response()->json($data) : view('admin.dashboard', compact('data'));
    }
}
