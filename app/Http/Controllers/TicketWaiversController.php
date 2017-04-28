<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;

class TicketWaiversController extends Controller
{
    public function store(Ticket $ticket)
    {
        $waiver = $ticket->waiver()->create([
            'provider' => 'adobesign'
        ]);

        return request()->expectsJson()
            ? response()->json($waiver, 201)
            : redirect()->back();
    }
}
