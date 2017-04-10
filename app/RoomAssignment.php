<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoomAssignment extends Pivot
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
