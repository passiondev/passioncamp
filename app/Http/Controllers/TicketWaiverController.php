<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\WaiverStatus;

class TicketWaiverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        if (!! request()->query('completed')) {
            $ticket->waivers->each(function ($waiver) {
                $waiver->delete();
            });

            $waiver = $ticket->waiver()->create([
                'provider' => 'offline',
                'status' => WaiverStatus::COMPLETE,
            ]);
        } else {
            $waiver = $ticket->createWaiver();
        }

        return request()->expectsJson()
            ? response()->json($waiver, 201)
            : redirect()->back();
    }
}
