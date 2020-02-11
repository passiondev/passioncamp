<?php

namespace App\Http\Controllers;

use App\Church;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\VerifyUserIsAdmin;
use App\Organization;
use App\Person;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware([Authenticate::class, VerifyUserIsAdmin::class]);
    }

    public function index()
    {
        $organizations = Organization::orderByChurchName()
            ->with([
                'church',
                'items',
                'settings',
                'contact',
                'activeAttendees' => function ($query) {
                    $query->with('roomAssignment', 'waiver');
                },
            ])
            ->withCount([
                'items as cost_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity * cost)'));
                },
                'tickets as tickets_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                },
                'transactions as paid_sum' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                },
                'hotelItems as hotels_sum' => function ($query) {
                    $query->select(DB::raw('SUM(quantity)'));
                },
                'rooms',
                'keyReceivedRooms',
                'checkedInRooms',
            ])
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
            'transactions',
            'authUsers.person',
            'notes',
            'attendees.waiver',
        ]);

        return view('super.organization.show', compact('organization'));
    }

    public function create()
    {
        $organization = (new Organization())
            ->church()->associate(new Church())
            ->contact()->associate(new Person())
            ->studentPastor()->associate(new Person());

        return view('super.organization.create', compact('organization'));
    }

    public function store()
    {
        $organization = (new Organization())
            ->church()->associate(
                Church::create(['name' => request()->input('name')])
            );

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

        if ($organization->contact->wasRecentlyCreated) {
            $organization->contact()->associate($organization->contact)->save();
        }

        if ($organization->studentPastor->wasRecentlyCreated) {
            $organization->studentPastor()->associate($organization->studentPastor)->save();
        }

        if ((bool) $organization->setting('checked_in') != (bool) request('checked_in')) {
            $organization->setting('checked_in', (bool) request('checked_in') ? time() : '');
        }

        return redirect()->action('OrganizationController@show', $organization)->with('success', 'Church updated.');
    }
}
