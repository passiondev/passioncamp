<?php

namespace App\Listeners;

use App\Events\OrgItemSaved;
use App\Jobs\Organization\DeployRooms;

class DispatchDeployRoomsForOrganization
{
    public function handle(OrgItemSaved $event)
    {
        if ('hotel' != $event->orgItem->org_type) {
            return;
        }

        return DeployRooms::dispatch($event->orgItem->organization);
    }
}
