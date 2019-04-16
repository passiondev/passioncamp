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

    public function createSignatureRequest(array $data)
    {
        $request = new TemplateSignatureRequest;
        $request->fromArray($data);

        if (app()->isLocal()) {
            $request->enableTestMode();
        }
        // $request->setCustomFieldValue('Cost', '$20,000');

        // $request->enableTestMode();
        // $request->setTemplateId($template->getId());
        // $request->setSubject('Purchase Order');
        // $request->setMessage('Glad we could come to an agreement.');
        // $request->setSigner('Client', 'george@example.com', 'George');
        // $request->setCC('Accounting', 'accounting@example.com');
        // $request->setCustomFieldValue('Cost', '$20,000');

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

    public function fetchPdf($agreementId) {
        return file_get_contents($this->client->getFiles($agreementId)->getFileUrl());
    }
}
