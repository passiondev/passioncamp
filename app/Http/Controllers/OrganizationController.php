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
        $organizations = Organization::orderByChurchName()
            ->with('church', 'settings')
            ->has('tickets')
            ->withCount(['activeAttendees', 'assignedToRoom', 'rooms', 'completedWaivers', 'checkedInRooms', 'keyReceivedRooms', 'settings'])
            ->scopes(['withTicketsSum', 'withHotelsSum', 'withCostSum', 'withPaidSum'])
            ->get();

        return view('super.organization.index', compact('organizations'));
    }

    public function search()
    {
        return Organization::searchByChurchName(request('query'))
            ->orderByChurchName()
            ->with('church')
            ->get()
            ->map(function ($organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->church->name,
                ];
            });
    }

    public function show(Organization $organization)
    {
        $organization->load([
            'church',
            'studentPastor',
            'contact',
            'items.item',
            'transactions.transaction',
            'authUsers.person',
            'notes',
            'attendees.waiver'
        ]);

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
        ;

        $organization->save();

        return redirect()->route('admin.organizations.show', $organization)->with('success', 'Church created.');
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

        if (!! $organization->setting('checked_in') != !! request('checked_in')) {
            $organization->setting('checked_in', !! request('checked_in') ? time() : '');
        }

        return redirect()->action('OrganizationController@show', $organization)->with('success', 'Church updated.');
    }
}
