<?php

namespace App\Http\Controllers;

use App\Room;
use App\Ticket;
use Illuminate\Http\Request;
use App\Repositories\RoomRepository;
use App\Repositories\TicketRepository;

class RoomingListController extends Controller
{
    public function index()
    {
        $unassigned = Ticket::forUser()->unassigned()->with('person')->get();
        $rooms = Room::forUser()->with('tickets.person')->get();

        return view('roominglist.index', compact('unassigned', 'rooms'));
    }
}
