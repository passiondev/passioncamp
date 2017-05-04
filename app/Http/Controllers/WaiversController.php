<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Waiver;
use Illuminate\Http\Request;
use App\Jobs\Waiver\SendReminder;

class WaiversController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tickets = Ticket::forUser(auth()->user())
            ->with('person', 'waiver', 'order.organization', 'order.user.person')
            ->active()
            ->join('people', 'order_items.person_id', '=', 'people.id')
            ->select('order_items.*')
            ->orderBy('people.last_name')
            ->orderBy('people.first_name')
            ->get();

        return view('waivers.index', compact('tickets'));
    }

    public function reminder(Waiver $waiver)
    {
        $this->authorize('view', $waiver);

        if (! $waiver->canBeReminded()) {
            abort(403, 'This waiver cannot be reminded.');
        }

        dispatch(new SendReminder($waiver));

        return request()->expectsJson()
            ? response([], 201)
            : redirect()->back();
    }

    public function destroy(Waiver $waiver)
    {
        $waiver->provider()->cancelSignatureRequest($waiver->provider_agreement_id);

        $waiver->delete();

        return request()->expectsJson()
            ? response([], 204)
            : redirect()->back();
    }
}
