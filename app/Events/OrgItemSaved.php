<?php

namespace App\Events;

use App\OrgItem;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrgItemSaved
{
    use Dispatchable, SerializesModels;

    public $orgItem;

    public function __construct(OrgItem $orgItem)
    {
        $this->orgItem = $orgItem;
    }
}
