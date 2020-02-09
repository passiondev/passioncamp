<?php

namespace App\Events;

use App\Room;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomDeleted
{
    use Dispatchable;
    use SerializesModels;

    public $room;

    public function __construct(Room $room)
    {
        $this->room = $room;
    }
}
