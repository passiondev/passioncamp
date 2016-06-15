<?php

namespace App\Repositories;

use App\Room;
use App\Ticket;

class RoomRepository
{
    public function create($organization, $name, $hotel_id = null)
    {
        $room = new Room;
        $room->name = $name;
        $room->hotel_id = $hotel_id;
        $room->capacity = $organization->room_capacity ?? 4;
        $room->organization()->associate($organization);
        $room->save();
    }

    public function update(Room $room, array $data)
    {
        $room->forceFill([
            'capacity' => array_get($data, 'capacity'),
            'description' => array_get($data, 'description'),
            'notes' => array_get($data, 'notes'),
        ])->save();
    }

    public function bulkCreate($organization)
    {
        // does the organization need rooms?
        // how many rooms does the org need?
        $needed = $organization->rooms_needed;
        $current = $organization->rooms->count();

        if (! $needed) {
            return;
        }

        // create the number of rooms needed
        for ($i = $current+1; $i <= $needed; $i++) {
            $this->create($organization, "Room #{$i}");
        }
     }

     public function assign(Room $room, Ticket $ticket)
     {
        if ($room->is_at_capacity) {
            throw new \Exception("{$room->name} is at capacity. {$ticket->name} has been added to the unassigned list.");
        }
        
        if ($room->organization_id !== $ticket->organization_id) {
            throw new \Exception("{$room->name} and {$ticket->name} do not belong to the same owner.");
        }
        
        $ticket->room()->associate($room)->save();
     }
}