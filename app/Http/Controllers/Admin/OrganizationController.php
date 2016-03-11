<?php

namespace App\Http\Controllers\Admin;

use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with('church', 'contact', 'tickets')->get();

        return view('admin.organization.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load('church', 'studentPastor', 'contact', 'items', 'transactions');

        return view('admin.organization.show', compact('organization'));
    }
}
