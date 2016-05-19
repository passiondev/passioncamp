<?php

namespace App\Listeners;

use App\Repositories\RoomRepository;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderItems\OrgItemUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRooms
{
    protected $rooms;

    public function __construct(RoomRepository $rooms)
    {
        $this->rooms = $rooms;
    }

    /**
     * Handle the event.
     *
     * @param  OrgItemUpdated  $event
     * @return void
     */
    public function handle(OrgItemUpdated $event)
    {
        // return if item is not a hotel
        if ($event->item->org_type != 'hotel') {
            return;
        }

        // return if item does not belong to an org
        if (! $event->item->organization_id) {
            return;
        }

        // create rooms for org
        $this->rooms->bulkCreate($event->item->organization);
    }
}
