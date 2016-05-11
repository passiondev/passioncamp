<?php

namespace App\Repositories;

use App\Room;

class RoomRepository
{
    public function create($organization, $name)
    {
        $room = new Room;
        $room->name = $name;
        $room->capacity = $organization->room_capacity ?? 4;
        $room->organization()->associate($organization);
        $room->save();
    }

    public function bulkCreate($organization)
    {
        // does the organization need rooms?
        // how many rooms does the org need?
        $needed = $organization->rooms_needed;
        $current = $organization->rooms()->count();

        if (! $needed) {
            return;
        }

        // create the number of rooms needed
        for ($i = $current+1; $i <= $needed; $i++) {
            $this->create($organization, "Room #{$i}");
        }
     }
}