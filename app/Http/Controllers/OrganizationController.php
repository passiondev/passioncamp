<?php

namespace App\Http\Controllers;

use Auth;
use App\Church;
use App\Person;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    public function __construct()
    {
        if (! (Auth::user() && Auth::user()->is_super_admin)) {
            abort(403);
        }
    }

    public function index()
    {
        $organizations = Organization::with('church', 'contact', 'tickets')->get();

        return view('admin.organization.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load('church', 'studentPastor', 'contact', 'items', 'transactions', 'authUsers');

        return view('admin.organization.show', compact('organization'));
    }

    public function create()
    {
        return view('admin.organization.create');
    }

    public function store(Request $request)
    {
        $organization = new Organization;

        // church
        $church = Church::create($request->church);
        $organization->church()->associate($church);
        
        // contact
        $contact = Person::create($request->contact);
        $organization->contact()->associate($contact);
        
        // student pastor
        $studentPastor = Person::create($request->student_pastor);
        $organization->studentPastor()->associate($studentPastor);
        
        $organization->save();

        return redirect()->route('admin.organization.show', $organization)->with('success', 'Church created.');
    }

    public function edit(Organization $organization)
    {
        return view('admin.organization.edit')->withOrganization($organization);
    }

    public function update(Request $request, Organization $organization)
    {
        $organization->church->fill($request->church)->save();
        $organization->contact->fill($request->contact)->save();
        $organization->studentPastor->fill($request->student_pastor)->save();

        return redirect()->route('admin.organization.show', $organization)->with('success', 'Church updated.');
    }
}
