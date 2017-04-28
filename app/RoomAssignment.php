<?php

namespace App;

class RoomAssignment extends Model
{
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
