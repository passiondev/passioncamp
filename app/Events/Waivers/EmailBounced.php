<?php

namespace App\Events\Waivers;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class EmailBounced
{
    use Dispatchable, SerializesModels;

    public $waiver;

    public function __construct($waiver)
    {
        $this->waiver = $waiver;
    }
}
