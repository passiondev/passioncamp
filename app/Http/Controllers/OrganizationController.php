<?php

namespace App\Http\Controllers;

use App\Church;
use App\Person;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with('contact', 'church', 'tickets', 'transactions.transaction', 'items', 'attendees.waiver')->get();

        return view('organization.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load('church', 'studentPastor', 'contact', 'items.item', 'transactions.transaction', 'authUsers', 'notes', 'attendees.waiver');

        if (is_null($organization->contact)) {
            $organization->contact = new Person;
        }

        if (is_null($organization->studentPastor)) {
            $organization->studentPastor = new Person;
        }

        return view('organization.show', compact('organization'));
    }

    public function create()
    {
        $organization = (new Organization)
            ->church()->associate(new Church)
            ->contact()->associate(new Person)
            ->studentPastor()->associate(new Person);

        return view('organization.create', compact('organization'));
    }

    public function store()
    {
        $organization = (new Organization)
            ->church()->associate(Church::create(request('church')))
            ->contact()->associate(Person::create(request('contact')))
            ->studentPastor()->associate(Person::create(request('student_pastor')))
        ->save();

        return redirect()->action('OrganizationController@show', $organization)->with('success', 'Church created.');
    }

    public function edit(Organization $organization)
    {
        return view('organization.edit')->withOrganization($organization);
    }

    public function update(Request $request, Organization $organization)
    {
        $organization->church->fill(request('church'))->save();
        $organization->contact->fill(request('contact'))->save();
        $organization->studentPastor->fill(request('student_pastor'))->save();

        return redirect()->action('OrganizationController@show', $organization)->with('success', 'Church updated.');
    }
}
