<?php

namespace App\Http\Controllers;

use App\Room;
use App\Ticket;
use App\RoomAssignment;
use Illuminate\Support\Facades\Validator;

class RoomAssignmentController extends Controller
{
    public function store(Room $room)
    {
        Validator::make(request()->all(), [
            'ticket' => 'required',
        ])->after(function ($validator) use ($room) {
            if ($room->assigned >= $room->capacity) {
                $validator->errors()->add('assigned', 'There are too many assigned to this room.');
            }
        })->validate();

        $ticket = Ticket::findOrFail(request('ticket'));

        throw_if($ticket->order->organization->isnot($room->organization), \Exception::class);

        $ticket->rooms()->detach();

        $room->tickets()->attach(request('ticket'));

        return response('Created.', 201);
    }
}
