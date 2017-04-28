<?php

namespace App\Contracts;

interface EsignProvider
{
    public function createSignatureRequest(array $data);
}
