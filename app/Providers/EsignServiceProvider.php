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
        } else {
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
                'agreement_read:self',
                'agreement_send:self',
                'agreement_write:self',
                'library_read:self',
            ],
        ]);

        $adobeSign = new AdobeSign($provider);

        $accessToken = optional($app['cache']->get('adobesign.token'), function ($token) {
            return new AccessToken(json_decode($token, true));
        });

        if (! $accessToken) {
            throw new \Exception('Adobe Sign not authorized.');
        }

        if ($accessToken->hasExpired()) {
            $accessToken = $adobeSign->refreshAccessToken($accessToken->getRefreshToken());
            $app['cache']->put('adobesign.token', json_encode($accessToken), now()->addDays(60));
        }

        $adobeSign->setAccessToken($accessToken->getToken());

        return new AdobeSignEsignProvider($adobeSign);
    }
}
