<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyUserIsChurchAdmin;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(VerifyUserIsChurchAdmin::class);
    }

    public function __invoke(Request $request)
    {
        $organization = $request->user()->organization()->with('items.item')->first();

        return view('account.dashboard', compact('organization'));
    }
}
