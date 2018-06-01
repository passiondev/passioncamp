<?php

namespace App\Http\Controllers\Super;

use App\Organization;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $organizations = Organization::with('settings')->get();

        $data = [
            'num_churches' => Organization::active()->count(),
            'num_tickets' => Organization::withTicketsSum()->get()->sum('tickets_sum'),
            'total_cost' => Organization::withCostSum()->get()->sum('cost_sum') / 100,
            'total_paid' => Organization::withPaidSum()->get()->sum('paid_sum') / 100,
            'stripe' => Organization::withPaidSum('stripe')->get()->sum('stripe_paid_sum') / 100,
            'other' => Organization::withPaidSum('other')->get()->sum('other_paid_sum') / 100,
            'balance' => $organizations->sum('cached_balance') / 100,
            'tickets_sum' => $organizations->sum('cached_tickets_sum'),
            'active_attendees_count' => $organizations->sum('cached_active_attendees_count'),
            'assigned_to_room_count' => $organizations->sum('cached_assigned_to_room_count'),
            'completed_waivers_count' => $organizations->sum('cached_completed_waivers_count'),
            'rooms_count' => $organizations->sum('cached_rooms_count'),
            'key_received_rooms_count' => $organizations->sum('cached_key_received_rooms_count'),
            'is_checked_in' => $organizations->where('is_checked_in', true)->count(),
        ];

        $data['balance'] = $data['total_cost'] - $data['total_paid'];

        return request()->wantsJson()
            ? response()->json($data)
            : view('admin.dashboard', compact('data'));
    }
}
