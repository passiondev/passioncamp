<?php

namespace App\Observers;

use App\Waiver;
use App\Jobs\Waiver\AdobeSign\RequestWaiverSignature;

class WaiverObserver
{
    public function created(Waiver $waiver)
    {
        dispatch(new RequestWaiverSignature($waiver));
    }
}
