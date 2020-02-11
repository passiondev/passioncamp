<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Room;
use App\Ticket;
use Illuminate\Support\Facades\Validator;

class RoomAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function store(Room $room)
    {
        $this->authorize('update', $room);

        Validator::make(request()->all(), [
            'ticket' => 'required',
        ])->after(function ($validator) use ($room) {
            if ($room->assigned >= $room->capacity) {
                $validator->errors()->add('assigned', 'There are too many assigned to this room.');
            }
        })->validate();

        $ticket = Ticket::findOrFail(request('ticket'));

        $this->authorize('update', $ticket);

        throw_if($ticket->order->organization->isnot($room->organization), \Exception::class);

        $ticket->rooms()->detach();

        $room->tickets()->attach(request('ticket'));

        return response('Created.', 201);
    }
}
