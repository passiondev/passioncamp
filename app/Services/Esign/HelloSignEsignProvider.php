<?php

namespace App\Services\Esign;

use App\Contracts\EsignProvider;
use HelloSign\Client as HelloSignClient;
use HelloSign\TemplateSignatureRequest;
use HelloSign;

class HelloSignEsignProvider
{
    public $client;

    public function __construct(HelloSignClient $client)
    {
        $this->client = $client;
    }

    public function createSignatureRequest(array $data)
    {

        $client = new HelloSign\Client('6277bf8f3d2feb73a5384f0744d493fe60f97129b5383456bc218403ef1d329a');
        $request = new HelloSign\TemplateSignatureRequest;
        $request->enableTestMode();
        $request->setTemplateId('d670b0e6610cd423b4e56413510036369fc58eae');
        $request->setSubject('Purchase Order');
        $request->setMessage('Glad we could come to an agreement.');
        $request->setSigner('Adult Participant or Parent / Guardian of Minor Participant', 'matt.floyd@268generation.com', 'George');
        $request->setCustomFieldValue('Church Name', 'Example church name');
        dump($request);
        $response = $client->sendTemplateSignatureRequest($request);
        dump($response);
    }

    public function sendReminder($agreementId) {}

    public function cancelSignatureRequest($agreementId) {}

    public function fetchStatus($agreementId) {}

    public function fetchPdf($agreementId) {}
}
