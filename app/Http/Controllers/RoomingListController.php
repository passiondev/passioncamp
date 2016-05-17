<?php

namespace App\Http\Controllers;

use App\Room;
use Exception;
use App\Ticket;
use Illuminate\Http\Request;
use App\Repositories\RoomRepository;
use App\Repositories\TicketRepository;

class RoomingListController extends Controller
{
    private $rooms;

    public function __construct(RoomRepository $rooms)
    {
        $this->rooms = $rooms;
    }

    public function index()
    {
        $unassigned = Ticket::active()->forUser()->unassigned()->with('person')->orderBy('agegroup')->get()->unassigendSort();
        $rooms = Room::forUser()->with('tickets.person')->get();

        return view('roominglist.index', compact('unassigned', 'rooms'));
    }

    public function show(Request $request, Room $room)
    {
        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'view' => view('roominglist.partials.room')->withRoom($room->fresh('tickets'))->render()
            ])
            : redirect()->route('roominglist.index');
    }

    public function assign(Request $request, Ticket $ticket, Room $room)
    {
        try {
            $this->rooms->assign($room, $ticket);
        } catch (Exception $e) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => $e->getMessage(), 
                    'view' => view('roominglist.partials.room')->withRoom($room->fresh('tickets'))->render()
                ], 400)
                : abort(400, $e->getMessage());
        }

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'view' => view('roominglist.partials.room')->withRoom($room->fresh('tickets'))->render()
            ])
            : redirect()->route('roominglist.index');
    }

    public function unassign(Request $request, Ticket $ticket)
    {
        $ticket->room_id = null;
        $ticket->save();
    }

}
