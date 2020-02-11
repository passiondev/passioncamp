<?php

namespace App\Services\Esign;

use App\Contracts\EsignProvider;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class LogEsignProvider implements EsignProvider
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function createSignatureRequest(array $data)
    {
        $this->logger->info($data);

        return Str::random(30);
    }

    public function sendReminder($agreementId)
    {
        $this->logger->info($agreementId);

        return 'reminder-sent';
    }

    public function cancelSignatureRequest($agreementId)
    {
        $this->logger->info($agreementId);
    }

    public function fetchStatus($agreementId)
    {
        return 'complete';
    }

    public function fetchPdf($agreementId)
    {
        return 'Hello World';
    }
}
