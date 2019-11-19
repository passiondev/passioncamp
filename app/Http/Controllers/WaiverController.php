<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Waiver;
use App\Organization;
use Illuminate\Http\Request;
use App\Filters\TicketFilters;
use App\Jobs\Waiver\SendReminder;
use App\Http\Middleware\Authenticate;

class WaiverController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function index(TicketFilters $filters)
    {
        $this->authorize('create', Waiver::class);

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

    public function reminder(Waiver $waiver)
    {
        $this->authorize('remind', $waiver);

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
        $this->authorize('delete', $waiver);

        $waiver->delete();

        return request()->expectsJson()
            ? response([], 204)
            : redirect()->back();
    }
}
