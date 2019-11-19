<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyUserIsChurchAdmin;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(VerifyUserIsChurchAdmin::class);
    }

    public function __invoke()
    {
        $organization = auth()->user()->organization;
        $organization->load('authUsers.person');

        return view('account.settings.index')->withOrganization($organization);
    }
}
