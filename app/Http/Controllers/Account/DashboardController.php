<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        $organization = auth()->user()->organization()
            ->with('items.item')
            ->withCount('students', 'leaders', 'activeAttendees')
            ->scopes(['withTicketsSum', 'withCostSum', 'withPaidSum'])
            ->first();

        return view('account.dashboard')->withOrganization($organization);
    }
}
