<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.magic');
    }

    public function __invoke()
    {
        $tickets = auth()->user()->tickets()
            ->active()
            ->with('person', 'order.organization.church')
            ->get();

        return view('user.dashboard', [
            'user' => auth()->user(),
            'tickets' => $tickets,
        ]);
    }
}
