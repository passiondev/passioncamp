<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function home()
    {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->action('Super\DashboardController');
        }

        return redirect()->action('Account\DashboardController');
    }
}
