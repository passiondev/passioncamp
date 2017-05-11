<?php

namespace App\Services\Esign;

use App\Contracts\EsignProvider;
use KevinEm\AdobeSign\AdobeSign as AdobeSignClient;

class AdobeSignEsignProvider implements EsignProvider
{
    protected $adobesign;

    public function __construct(AdobeSignClient $adobesign)
    {
        $this->adobesign = $adobesign;
    }

    public function createSignatureRequest(array $data)
    {
        \Log::debug($data);

        $response = $this->adobesign->createAgreement($data);

        return $response['agreementId'];
    }

    public function sendReminder($agreementId)
    {
        $response = $this->adobesign->sendReminder([
            'agreementId' => $agreementId,
        ]);

        return $response['result'];
    }

    public function cancelSignatureRequest($agreementId)
    {
        $response = $this->adobesign->deleteAgreement($agreementId);

        return $response['result'];
    }

    public function fetchStatus($agreementId)
    {
        $response = $this->adobesign->getAgreement($agreementId);

        return $response['status'];
    }

    public function fetchPdf($agreementId)
    {
        return $this->adobesign->getAgreementCombinedDocument($agreementId);
    }
}
