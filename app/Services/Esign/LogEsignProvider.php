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

    public function createSignatureRequest(array $data)
    {
        $this->logger->info($data);

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

    public function fetchStatus($agreementId)
    {
        return 'complete';
    }

    public function fetchPdf($agreementId)
    {
        return 'Hello World';
    }

    public function updateAgreementStatus($agreementId, array $agreementStatusUpdateInfo)
    {
        $this->logger->info($agreementId, $agreementStatusUpdateInfo);

        return [
            'body' => [
                'result' => 'CANCELED',
            ],
        ];
    }
}
