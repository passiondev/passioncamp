<?php

namespace App\Events;

use App\OrgItem;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrgItemSaved
{
    use Dispatchable;
    use SerializesModels;

    public $orgItem;

    public function __construct(OrgItem $orgItem)
    {
        $this->orgItem = $orgItem;
    }
}
