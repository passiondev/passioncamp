<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Waiver;
use App\Organization;
use Illuminate\Http\Request;
use App\Filters\TicketFilters;
use App\Jobs\Waiver\SendReminder;
use App\Jobs\Waiver\FetchAndUpdateStatus;

class WaiversController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(TicketFilters $filters)
    {
        $tickets = Ticket::forUser(auth()->user())
            ->filter($filters)
            ->with('person', 'waiver', 'order.organization.church', 'order.user.person')
            ->active()
            ->join('people', 'order_items.person_id', '=', 'people.id')
            ->select('order_items.*')
            ->orderBy('people.last_name')
            ->orderBy('people.first_name');
        $tickets = $filters->hasFilters() || auth()->user()->isChurchAdmin() ? $tickets->get() : $tickets->paginate();

        $organizations = Organization::select('organizations.*')->with('church')->join('churches', 'church_id', '=', 'churches.id')->orderBy('churches.name')->withoutGlobalScopes()->get();

        return view('waivers.index', compact('tickets', 'organizations'));
    }

    public function refresh(Waiver $waiver)
    {
        dispatch(new FetchAndUpdateStatus($waiver));

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

        dispatch(new SendReminder($waiver));

        return request()->expectsJson()
            ? response($waiver, 201)
            : redirect()->back();
    }

    public function destroy(Waiver $waiver)
    {
        $waiver->delete();

        return request()->expectsJson()
            ? response([], 204)
            : redirect()->back();
    }
}
