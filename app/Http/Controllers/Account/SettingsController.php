<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $organization = auth()->user()->organization;
        $organization->load('authUsers.person');

        return view('organization.settings.index')->withOrganization($organization);
    }
}
