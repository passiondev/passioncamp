<?php

namespace App\Listeners\Waivers\EmailBounced;

use App\Events\Waivers\EmailBounced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Interactions\Echosign\Agreement;

class CancelSignatureRequest
{
    private $agreement;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Agreement $agreement)
    {
        //
        $this->agreement = $agreement;
    }

    /**
     * Handle the event.
     *
     * @param  EmailBounced  $event
     * @return void
     */
    public function handle(EmailBounced $event)
    {
        $this->agreement->cancel($event->waiver->documentKey);
    }
}
