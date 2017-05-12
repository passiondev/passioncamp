<?php

namespace App\Http\Controllers\Webhooks;

use App\Waiver;
use App\WaiverStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Waiver\FetchAndUpdateStatus;

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

        if ($waiver->status != WaiverStatus::COMPLETE) {
            dispatch(new FetchAndUpdateStatus($waiver));
        }

        return response($waiver, 200);
    }
}
