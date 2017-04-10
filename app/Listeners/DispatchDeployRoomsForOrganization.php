<?php

namespace App\Listeners;

use App\Events\OrgItemSaved;
use App\Jobs\Organization\DeployRooms;

class DispatchDeployRoomsForOrganization
{
    public function handle(OrgItemSaved $event)
    {
        if ($event->orgItem->org_type != 'hotel') {
            return;
        }

        return dispatch(new DeployRooms($event->orgItem->organization));
    }
}
