<?php

namespace App\Services\Esign;

use Psr\Log\LoggerInterface;
use App\Contracts\EsignProvider;

class LogEsignProvider implements EsignProvider
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function createSignatureRequest()
    {
        $this->logger->info([]);

        return str_random(30);
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
}
