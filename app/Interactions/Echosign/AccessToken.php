<?php

namespace App\Interactions\Echosign;

use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;

class AccessToken extends BaseAccessToken
{
    protected $apiBaseUri;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->apiBaseUri = $options['api_access_point'] ?? null;
    }

    public function getApiBaseUri()
    {
        return $this->apiBaseUri;
    }
}
