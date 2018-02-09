<?php

namespace App;

use App\Events\OrgItemSaved;

class OrgItem extends OrderItem
{
    protected $table = 'order_items';

    protected $dispatchesEvents = [
        'saved' => OrgItemSaved::class,
    ];
}
