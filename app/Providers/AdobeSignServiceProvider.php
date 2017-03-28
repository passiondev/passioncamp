<?php

namespace App\Providers;

use KevinEm\AdobeSign\AdobeSign;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Token\AccessToken;
use KevinEm\OAuth2\Client\AdobeSign as OAuth2Client;

class AdobeSignServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdobeSign::class, function ($app) {
            $provider = new OAuth2Client([
                'clientId' => config('services.adobesign.key'),
                'clientSecret' => config('services.adobesign.secret'),
                'scope' => [
                    'agreement_read',
                    'agreement_send',
                    'library_read',
                    'agreement_write',
                ]
            ]);

            $adobeSign = new AdobeSign($provider);

            // $app['cache']->flush();
            if ($app['cache']->has('adobesign.token')) {
                $accessToken = new AccessToken(json_decode($app['cache']->get('adobesign.token'), true));
            } else {
                $accessToken = $adobeSign->refreshAccessToken(config('services.adobesign.refresh'));

                $app['cache']->put('adobesign.token', json_encode($accessToken), 60);
            }

            $adobeSign->setAccessToken($accessToken->getToken());

            return $adobeSign;
        });
    }
}
