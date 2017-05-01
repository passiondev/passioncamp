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
        $organization = auth()->user()->organization()
            ->withCount('students', 'leaders')
            ->first();

        return view('account.dashboard')->withOrganization($organization);
    }
}
