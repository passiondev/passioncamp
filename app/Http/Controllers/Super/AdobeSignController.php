<?php

namespace App\Http\Controllers\Super;

use Illuminate\Support\Carbon;
use KevinEm\AdobeSign\AdobeSign;
use App\Http\Controllers\Controller;
use function GuzzleHttp\json_encode;
use KevinEm\OAuth2\Client\AdobeSign as OAuth2Client;

class AdobeSignController extends Controller
{
    public function __invoke()
    {
        optional(cache('adobesign.token'), function ($token) {
            dd($token);
        });

        $provider = new OAuth2Client([
            'clientId' => config('services.adobesign.key'),
            'clientSecret' => config('services.adobesign.secret'),
            'redirectUri' => secure_url('admin/adobesign'),
            'scope' => [
                'agreement_read:self',
                'agreement_send:self',
                'agreement_write:self',
                'library_read:self',
            ],
        ]);

        $adobeSign = new AdobeSign($provider);

        if (!request()->has('code')) {
            $authorizationUrl = $adobeSign->getAuthorizationUrl();
            // session()->forget('oauth2state');
            session()->flash('oauth2state', $provider->getState());
            echo $authorizationUrl;
        } elseif (!request()->has('state') || request('state') !== session('oauth2state')) {
            echo 'Invalid state';
            print_r([request('state'), session('oauth2state')]);
        } else {
            $accessToken = $adobeSign->getAccessToken(request('code'));
            cache()->forever('adobesign.token', json_encode($accessToken));
            echo 'Access Token: '.$accessToken->getToken().'<br>';
            echo 'Refresh Token: '.$accessToken->getRefreshToken().'<br>';
            echo 'Expires: '.Carbon::createFromTimestamp($accessToken->getExpires()).'<br>';
        }
    }
}
