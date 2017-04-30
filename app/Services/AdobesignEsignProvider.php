<?php

namespace App\Services;

use KevinEm\AdobeSign\AdobeSign;
use App\Contracts\EsignProvider as EsignProviderContract;

class AdobesignEsignProvider implements EsignProviderContract
{
    protected $adobesign;

    public function __construct(AdobeSign $adobesign)
    {
        $this->adobesign = $adobesign;
    }

    public function createSignatureRequest(array $data)
    {
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
}
