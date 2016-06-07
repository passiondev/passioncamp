<?php

namespace App\Listeners\Waivers\EmailBounced;

use App\Events\Waivers\EmailBounced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelWaiverRecord
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmailBounced  $event
     * @return void
     */
    public function handle(EmailBounced $event)
    {
        $event->waiver->cancel();
    }
}
