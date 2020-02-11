<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\Waiver\FetchAndUpdateStatus;
use App\Waiver;
use App\WaiverStatus;
use Illuminate\Http\Request;

class AdobeSignController extends Controller
{
    public function __invoke()
    {
        $this->validate(request(), [
            'documentKey' => 'required',
            'eventType' => 'required',
        ]);

        $waiver = Waiver::whereProvider('adobesign')
            ->where('provider_agreement_id', request('documentKey'))
            ->firstOrFail();

        if (WaiverStatus::COMPLETE != $waiver->status) {
            dispatch(new FetchAndUpdateStatus($waiver));
        }

        return response($waiver, 200);
    }
}
