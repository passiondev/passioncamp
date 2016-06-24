<?php

namespace App\Http\Controllers\Ticket;

use App\Ticket;
use App\Waiver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interactions\Echosign\Reminder;
use App\Interactions\Echosign\Agreement;

class WaiverController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function create(Request $request, Ticket $ticket)
    {
        $this->authorize('owner', $ticket->order);

        if (! $ticket->order->hasContactInfo()) {
            return $request->ajax() || $request->wantsJson()
                   ? response(['status' => 'Contact info missing'], 403)
                   : abort(403, 'Contact info missing.');
        }

        if ($ticket->waiver) {
            return $request->ajax() || $request->wantsJson()
                   ? response(['status' => $ticket->waiver->status], 403)
                   : abort(403, 'Waiver already exists for ticket.');
        }

        $agreement = new Agreement;
        $agreementId = $agreement->create($ticket->order->user->person->email, [
            'name' => $ticket->person->name,
            'agegroup' => $ticket->agegroup,
            'church' => $ticket->organization->church->name,
            'location' => $ticket->organization->church->location,
        ]);

        $ticket->waiver()->save(
            new Waiver(['documentKey' => $agreementId])
        );

        return $request->ajax() || $request->wantsJson()
               ? response()->json(['status' => $ticket->load('waiver')->waiver->status])
               : redirect()->back();
    }

    public function reminder(Request $request, Ticket $ticket)
    {
        $this->authorize('owner', $ticket->order);

        $reminder = new Reminder;
        $response = $reminder->create($ticket->waiver->documentKey);
        if ($response) {
            $ticket->waiver->status = $response;
            $ticket->waiver->save();
        } else {
            \Log::error(print_r($response->getErrorMessages(), true));
        }

        return $request->ajax() || $request->wantsJson()
               ? response()->json(['status' => $ticket->load('waiver')->waiver->status])
               : redirect()->back();
    }

    public function cancel(Request $request, Ticket $ticket, Agreement $agreement)
    {
        $this->authorize('owner', $ticket->order);

        abort_unless(\Auth::user()->isSuperAdmin(), 403);

        $ticket->waivers->each(function ($waiver) use ($agreement) {
            $agreement->cancel($waiver->documentKey);
            $waiver->cancel();
        });

        return redirect()->back();
    }

    public function complete(Request $request, Ticket $ticket)
    {
        $this->authorize('owner', $ticket->order);

        abort_unless(\Auth::user()->isSuperAdmin(), 403);

        $ticket->waiver->complete();

        return redirect()->back();
    }
}
