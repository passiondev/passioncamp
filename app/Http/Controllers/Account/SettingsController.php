<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        $organization = auth()->user()->organization;
        $organization->load('authUsers.person');

        return view('account.settings.index')->withOrganization($organization);
    }
}
