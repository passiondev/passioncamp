<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Contracts\EsignProvider as EsignProviderContract;

class LogEsignProvider implements EsignProviderContract
{
    public function createSignatureRequest(array $data)
    {
        \Log::info($data);

        return str_random(30);
    }

    public function sendReminder($agreementId)
    {
        \Log::info($agreementId);

        return 'reminder-sent';
    }
}
