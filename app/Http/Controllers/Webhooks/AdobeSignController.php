<?php

namespace App\Http\Controllers\Webhooks;

use App\Waiver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdobeSignController extends Controller
{
    public function __invoke()
    {
        $waiver = Waiver::whereProvider('adobesign')
            ->where('provider_agreement_id', request('agreementId'))
            ->firstOrFail();

        if (request()->has('status')) {
            $waiver->update([
                'status' => request('status')
            ]);
        }

        return response($waiver, 200);
    }
}
