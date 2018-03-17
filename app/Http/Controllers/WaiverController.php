<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Waiver;
use App\Organization;
use Illuminate\Http\Request;
use App\Filters\TicketFilters;
use App\Jobs\Waiver\SendReminder;
use App\Jobs\Waiver\FetchAndUpdateStatus;

class WaiverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(TicketFilters $filters)
    {
        $tickets = Ticket::forUser(auth()->user())
            ->active()
            ->when(auth()->user()->isSuperAdmin(), function ($q) use ($filters) {
                $q->filter($filters);
            })
            ->with('person', 'waiver', 'order.organization.church', 'order.user.person')
            ->orderByPersonName();

        $tickets = $filters->hasFilters() || auth()->user()->isChurchAdmin()
            ? $tickets->get()
            : $tickets->paginate();

        $organizations = Organization::orderByChurchName()->with('church')->get();

        return view('waivers.index', compact('tickets', 'organizations'));
    }

    public function refresh(Waiver $waiver)
    {
        $this->authorize('view', $waiver);

        FetchAndUpdateStatus::dispatch($waiver);

        return request()->expectsJson()
            ? $waiver
            : redirect()->back();
    }

    public function reminder(Waiver $waiver)
    {
        $this->authorize('view', $waiver);

        if (! $waiver->canBeReminded()) {
            abort(403, 'This waiver cannot be reminded.');
        }

        SendReminder::dispatch($waiver);

        return request()->expectsJson()
            ? response($waiver, 201)
            : redirect()->back();
    }

    public function destroy(Waiver $waiver)
    {
        $this->authorize($waiver);

        $waiver->delete();

        return request()->expectsJson()
            ? response([], 204)
            : redirect()->back();
    }
}
