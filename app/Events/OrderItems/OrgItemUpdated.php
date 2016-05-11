<?php

namespace App\Events\OrderItems;

use App\Events\Event;

class OrgItemUpdated extends Event
{
    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }
}
