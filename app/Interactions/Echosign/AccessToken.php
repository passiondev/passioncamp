<?php

namespace App\Interactions\Echosign;

use League\OAuth2\Client\Token\AccessToken as BaseAccessToken;

class AccessToken extends BaseAccessToken
{
    protected $apiBaseUri;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
        
        if (!empty($options['api_access_point'])) {
            $this->apiBaseUri = $options['api_access_point'];
        }
    }

    public function getApiBaseUri()
    {
        return $this->apiBaseUri;
    }
}
