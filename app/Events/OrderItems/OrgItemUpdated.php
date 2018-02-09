<?php

namespace App\Events\OrderItems;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrgItemUpdated
{
    use Dispatchable, SerializesModels;

    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }
}
