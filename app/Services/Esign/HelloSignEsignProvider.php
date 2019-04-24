<?php

namespace App\Services\Esign;

use App\Contracts\EsignProvider;
use HelloSign\Client as HelloSignClient;
use HelloSign\TemplateSignatureRequest;

class HelloSignEsignProvider
{
    public $client;

    public function __construct(HelloSignClient $client)
    {
        $this->client = $client;
    }

    public function createSignatureRequest(TemplateSignatureRequest $request)
    {
        return $this->client->sendTemplateSignatureRequest($request);
    }

    public function sendReminder($agreementId, $email) {
        $this->client->requestEmailReminder($agreementId, $email);
    }

    public function cancelSignatureRequest($agreementId) {
        $this->client->cancelSignatureRequest($agreementId);
    }

    public function fetchStatus($agreementId) {
        return $this->client->getSignatureRequest($agreementId);
    }

    public function getSignatureRequest($agreementId) {
        return $this->client->getSignatureRequest($agreementId);
    }

    public function fetchPdf($agreementId) {
        return file_get_contents($this->client->getFiles($agreementId)->getFileUrl());
    }

    public function updateSignatureRequest($agreementId, $signatureId, $email)
    {
        $this->client->updateSignatureRequest($agreementId, $signatureId, $email);
    }
}
