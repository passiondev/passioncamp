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

        Ticket::find(request('ticket'))->rooms()->detach();

        $room->tickets()->attach(request('ticket'));

        return response('Created.', 201);
    }

    public function update(Room $room)
    {
        $this->validate(request(), [
            'tickets' => "present|max:{$room->capacity}",
        ]);

        $room->tickets()->sync(request('tickets'));

        return response('Updated.', 204);
    }

    public function delete(Room $room)
    {
        $this->validate(request(), [
            'ticket' => "required",
        ]);

        $room->tickets()->detach(request('ticket'));

        return response('Deleted.', 204);
    }
}
