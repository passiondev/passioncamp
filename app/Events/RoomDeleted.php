<?php

namespace App\Events;

use App\Room;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

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
