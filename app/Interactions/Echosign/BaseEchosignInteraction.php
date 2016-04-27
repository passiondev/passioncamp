<?php

namespace App\Interactions\Echosign;

use App\Interactions\Echosign\Provider;
use App\Interactions\Echosign\AccessToken;

abstract class BaseEchosignInteraction
{
    protected $token = '3AAABLblqZhBuivyuNPElxMIE5IHrsN0fQVxQdfhYFcf7LjIrCuElLMIDvR7vAGJ5JmE2Y22TN3WdHmyt2EFMX9gsUUZ5R-Xy';

    public function __construct()
    {
        $this->generateToken();
    }

    protected function generateToken()
    {
        $provider = new Provider([
            'clientId'                => 'CBJCHBCAABAAZGBwHutkhmyBZGaOpFdVGKlyR5POt183',
            'clientSecret'            => 'q947CW0KP_BtZqi401EFobhA1QDRiu2f',
            'redirectUri'             => 'https://camp.dev:44300/refresh',
            'urlAuthorize'            => 'https://secure.na1.echosign.com/public/oauth',
            'scopes'                  => ['agreement_read', 'agreement_send', 'library_read', 'agreement_write'],
        ]);

        $accessToken = new AccessToken([
            'access_token' => $this->token,
            'refresh_token' => '3AAABLblqZhA1sXd6tdoAw4iQigaSOPQXtmDY0UjWeETW5idxnVsYI772OB1YidWKaVZe2-_1Nek*',
            'expires' => 1461792738,
            'api_access_point' => 'api.na1.echosign.com'
        ]);

        if ($accessToken->hasExpired()) {
            $provider->setApiAccessPoint($accessToken->getApiBaseUri());

            $accessToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken(),
            ]);
        }
        
        $this->token = $accessToken->getToken();
    }}