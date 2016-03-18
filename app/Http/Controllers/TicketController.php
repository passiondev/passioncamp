<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('person')->paginate(15);

        return view('ticket.index', compact('tickets'));
    }
}
