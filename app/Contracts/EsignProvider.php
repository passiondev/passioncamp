<?php

namespace App\Contracts;

interface EsignProvider
{
    public function createSignatureRequest(array $data);

    public function sendReminder($agreementId);

    public function cancelSignatureRequest($agreementId);

    public function fetchStatus($agreementId);

    public function fetchPdf($agreementId);
}
