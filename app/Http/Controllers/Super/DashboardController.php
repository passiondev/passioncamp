<?php

namespace App\Http\Controllers\Super;

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
        return view('admin.dashboard');
    }
}
