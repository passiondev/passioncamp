n<?php

namespace App\Http\Controllers\Organization;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Organization\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view('organization.settings.index')->withOrganization($this->organization);
    }
}
