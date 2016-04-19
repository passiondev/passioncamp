<?php

namespace App\Http\Controllers\Ticket;

use App\Ticket;
use App\Http\Controllers\Controller;
use App\Interactions\Echosign\Agreement;

class WaiverController extends Controller
{

    public function __construct()
    {
        $this->authorize('owner', request()->ticket->order);
    }

    public function create(Ticket $ticket)
    {
        $agreement = new Agreement;
        $agreementId = $agreement->create($ticket->order->user->person->email, [
            'name' => $ticket->person->name,
            'agegroup' => $ticket->agegroup,
            'church' => $ticket->organization->church->name,
            'location' => $ticket->organization->church->location,
        ]);

        $ticket->forceFill([
            'signature_request_id' => $agreementId,
            'signature_request_sent_at' => \Carbon\Carbon::now(),
            'signature_request_status' => $agreement->getStatus($agreementId),
        ])->save();

        return redirect()->back();
    }
}
