<?php

namespace App\Providers;

use App\Contracts\EsignProvider;
use KevinEm\AdobeSign\AdobeSign;
use Illuminate\Support\ServiceProvider;
use App\Services\Esign\LogEsignProvider;
use League\OAuth2\Client\Token\AccessToken;
use App\Services\Esign\AdobeSignEsignProvider;
use KevinEm\OAuth2\Client\AdobeSign as OAuth2Client;

class EsignServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->environment('production')) {
            $this->app->bind(AdobeSignEsignProvider::class, function ($app) {
                return $this->resolveAdobeSignEsignProvider($app);
            });

            $this->app->bind(EsignProvider::class, AdobeSignEsignProvider::class);
        }
        else {
            $this->app->bind(EsignProvider::class, AdobeSignEsignProvider::class);
            $this->app->bind(AdobeSignEsignProvider::class, LogEsignProvider::class);
        }
    }

    public function resolveAdobeSignEsignProvider($app)
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

        return new AdobeSignEsignProvider($adobeSign);
    }
}
