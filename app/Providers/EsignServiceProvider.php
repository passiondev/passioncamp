<?php

namespace App\Providers;

use KevinEm\AdobeSign\AdobeSign;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Token\AccessToken;
use KevinEm\OAuth2\Client\AdobeSign as OAuth2Client;

class EsignServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \App\Contracts\EsignProvider::class,
            \App\Services\AdobesignEsignProvider::class
        );

        $this->app->bind(\App\Services\AdobesignEsignProvider::class, function ($app) {
            return new \App\Services\AdobesignEsignProvider(
                $this->resolveAdobeSignClient($app)
            );
        });


        if (! $this->app->environment('production')) {
            $this->app->bind(
                \App\Services\AdobesignEsignProvider::class,
                \App\Services\LogEsignProvider::class
            );
        }
    }

    public function resolveAdobeSignClient($app)
    {
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

        if ($app['cache']->has('adobesign.token')) {
            $accessToken = new AccessToken(json_decode($app['cache']->get('adobesign.token'), true));
        } else {
            $accessToken = $adobeSign->refreshAccessToken(config('services.adobesign.refresh'));

            $app['cache']->put('adobesign.token', json_encode($accessToken), 60);
        }

        $adobeSign->setAccessToken($accessToken->getToken());

        return $adobeSign;
    }
}
