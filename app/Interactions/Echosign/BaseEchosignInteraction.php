<?php

namespace App\Interactions\Echosign;

use App\Interactions\Echosign\Provider;
use App\Interactions\Echosign\AccessToken;

abstract class BaseEchosignInteraction
{
    protected $token = '3AAABLblqZhCJTbR4G8CYBChHEMPBP5EQZ3sDDd_e-X46-vXnrmm3scFZBcy7vuswfzJ4fxZPCo_UDra_mN-RwCDItHZxmVVo';

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
            'scopes'                  => ['agreement_read', 'agreement_send', 'library_read'],
        ]);

        $accessToken = new AccessToken([
            'access_token' => $this->token,
            'refresh_token' => '3AAABLblqZhB9gaUV8EvuGXWLtz4hJoTJIkyT3nvl4awMUSkvxzDVLt1ClxMpfHQXWRqSegjnLu4*',
            'expires' => 1458580895,
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