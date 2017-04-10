<?php

namespace App\Listeners;

use App\Events\RoomDeleted;

class DetachRoomTickets
{
    public function handle(RoomDeleted $event)
    {
        $event->room->tickets()->detach();
    }
}
