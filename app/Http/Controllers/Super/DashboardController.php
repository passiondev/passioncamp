<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Organization;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $activeOrganizations = Organization::query()
            ->active()
            ->with([
                'church',
                'settings',
                'contact',
                'activeAttendees' => function ($query) {
                    $query->with('roomAssignment', 'waiver');
                },
            ])
            ->withCount([
                'items as cost_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity * cost)'));
                },
                'tickets as tickets_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                },
                'transactions as paid_sum' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                },
                'hotelItems as hotels_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                },
                'rooms',
                'keyReceivedRooms',
                'checkedInRooms',
            ])
            ->get();

        $allOrganizations = Organization::query()
            ->withCount([
                'items as cost_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity * cost)'));
                },
                'transactions as paid_sum' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                },
                'transactions as stripe_paid_sum' => function ($query) {
                    $query->select(DB::raw('SUM(transaction_splits.amount)'))
                        ->join('transactions', 'transaction_id', 'transactions.id')
                        ->where('source', 'stripe');
                },
                'transactions as other_paid_sum' => function ($query) {
                    $query->select(DB::raw('SUM(transaction_splits.amount)'))
                        ->join('transactions', 'transaction_id', 'transactions.id')
                        ->where('source', 'other');
                },
            ])
            ->get();

        $data = [
            'num_churches' => $activeOrganizations->count(),
            'num_tickets' => $activeOrganizations->sum('tickets_sum'),
            'total_cost' => $allOrganizations->sum('cost_sum') / 100,
            'total_paid' => $allOrganizations->sum('paid_sum') / 100,
            'stripe' => $allOrganizations->sum('stripe_paid_sum') / 100,
            'other' => $allOrganizations->sum('other_paid_sum') / 100,
            'balance' => $activeOrganizations->sum('balance') / 100,
            'tickets_sum' => $activeOrganizations->sum('tickets_sum'),
            'active_attendees_count' => $activeOrganizations->sum('active_attendees_count'),
            'assigned_to_room_count' => $activeOrganizations->sum('assigned_to_room_count'),
            'completed_waivers_count' => $activeOrganizations->sum('completed_waivers_count'),
            'rooms_count' => $activeOrganizations->sum('rooms_count'),
            'key_received_rooms_count' => $activeOrganizations->sum('key_received_rooms_count'),
            'is_checked_in' => $activeOrganizations->where('is_checked_in', true)->count(),
        ];

        $data['balance'] = $data['total_cost'] - $data['total_paid'];

        return request()->wantsJson()
            ? response()->json($data)
            : view('admin.dashboard', compact('data'));
    }
}
