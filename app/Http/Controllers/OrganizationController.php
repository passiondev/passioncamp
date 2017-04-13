<?php

namespace App\Http\Controllers;

use App\Church;
use App\Person;
use App\Organization;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index()
    {
        $organizations = Organization::join('churches', 'organizations.church_id', '=', 'churches.id')
            ->with('church', 'transactions.transaction', 'items')
            ->withCount(['activeAttendees', 'assignedToRoom', 'rooms'])
            ->withTicketsSum()
            ->withHotelsSum()
            ->orderBy('name')
            ->get();

        return view('super.organization.index', compact('organizations'));
    }

    public function search()
    {
        return Organization::join('churches', 'organizations.church_id', '=', 'churches.id')->with('church')->where('name', 'LIKE', request('query') . '%')->orderBy('name')->get()->map(function ($organization) {
            return [
                'id' => $organization->id,
                'name' => $organization->church->name,
            ];
        });
    }

    public function show(Organization $organization)
    {
        $organization->load('church', 'studentPastor', 'contact', 'items.item', 'transactions.transaction', 'authUsers', 'notes', 'attendees.waiver');

        return view('super.organization.show', compact('organization'));
    }

    public function create()
    {
        $organization = (new Organization)
            ->church()->associate(new Church)
            ->contact()->associate(new Person)
            ->studentPastor()->associate(new Person);

        return view('super.organization.create', compact('organization'));
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
        return view('super.organization.edit')->withOrganization($organization);
    }

    public function update(Organization $organization)
    {
        $organization->church->fill(request('church'))->save();
        $organization->contact->fill(request('contact'))->save();
        $organization->studentPastor->fill(request('student_pastor'))->save();

        return redirect()->action('OrganizationController@show', $organization)->with('success', 'Church updated.');
    }
}
