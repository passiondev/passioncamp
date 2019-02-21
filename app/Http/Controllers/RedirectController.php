<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.magic');
    }

    public function home()
    {
        if (auth()->user()->isSuperAdmin()) {
            return redirect()->action('Super\DashboardController');
        }

        if (auth()->user()->isChurchAdmin()) {
            return redirect()->action('Account\DashboardController');
        }

        return redirect()->action('User\DashboardController');
    }
}
