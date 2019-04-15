<?php

namespace App\Services\Esign;

class ProviderFactory
{
    public function make($provider)
    {
        switch ($provider) {
            case 'adobesign':
                return resolve(AdobeSignEsignProvider::class);
            case 'hellosign':
                return resolve(HelloSignEsignProvider::class);
        }
    }
}
