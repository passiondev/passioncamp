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
        $this->authorize('owner', request()->ticket->order);
    }

    public function create(Request $request, Ticket $ticket)
    {
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
        $reminder = new Reminder;
        $response = $reminder->create($ticket->waiver->documentKey);

        return $request->ajax() || $request->wantsJson()
               ? response()->json(['status' => $ticket->load('waiver')->waiver->status])
               : redirect()->back();
    }
}
