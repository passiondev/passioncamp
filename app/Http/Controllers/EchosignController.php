<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Echosign\BaseUris;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interactions\Echosign\Callback;
use Echosign\Transports\GuzzleTransport;
use League\OAuth2\Client\Token\AccessToken;

class EchosignController extends Controller
{
    protected $callback;

    public function __construct(Callback $callback)
    {
        $this->callback = $callback;
    }

    public function callback(Request $request)
    {
        if ($this->callback->validator($request->all())->fails()) {
            return '';
        }

        $this->callback->handle($request->all());
    }

    public function test()
    {
        $provider = new Provider([
            'clientId'                => 'CBJCHBCAABAAZGBwHutkhmyBZGaOpFdVGKlyR5POt183',
            'clientSecret'            => 'q947CW0KP_BtZqi401EFobhA1QDRiu2f',
            'redirectUri'             => 'https://camp.dev:44300/test',
            'urlAuthorize'            => 'https://secure.na1.echosign.com/public/oauth',
            'scopes'                  => ['agreement_read', 'agreement_send', 'agreement_write', 'library_read'],
        ]);

        if (isset($_GET['error'])) {
            return $_GET['error'];
        }
        
        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;
        } else {
            // Try to get an access token using the authorization code grant.
            $provider->setApiAccessPoint($_GET['api_access_point']);
            $accessToken = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);
dd(json_encode($accessToken));
        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        // echo $accessToken->getToken() . "\n";
        echo $accessToken->getRefreshToken() . "\n";
        // echo $accessToken->getExpires() . "\n";
        // echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";
        }


    }

    public function refresh()
    {
        $provider = new \App\Interactions\Echosign\Provider([
            'clientId'                => 'CBJCHBCAABAAZGBwHutkhmyBZGaOpFdVGKlyR5POt183',
            'clientSecret'            => 'q947CW0KP_BtZqi401EFobhA1QDRiu2f',
            'redirectUri'             => 'https://camp.dev:44300/refresh',
            'urlAuthorize'            => 'https://secure.na1.echosign.com/public/oauth',
            'scopes'                  => ['agreement_read', 'agreement_send', 'agreement_write', 'library_read'],
        ]);

        $accessToken = new \App\Interactions\Echosign\AccessToken(json_decode('{"access_token":"3AAABLblqZhCJTbR4G8CYBChHEMPBP5EQZ3sDDd_e-X46-vXnrmm3scFZBcy7vuswfzJ4fxZPCo_UDra_mN-RwCDItHZxmVVo","refresh_token":"3AAABLblqZhB9gaUV8EvuGXWLtz4hJoTJIkyT3nvl4awMUSkvxzDVLt1ClxMpfHQXWRqSegjnLu4*","expires":1458580895,"api_access_point":"api.na1.echosign.com"}', true));

        if ($accessToken->hasExpired()) {
            $provider->setApiAccessPoint($accessToken->getApiBaseUri());

            $accessToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken(),
            ]);
        }
dd($accessToken->getToken());
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.na1.echosign.com/api/rest/v5/'
        ]);
        // $res = $client->request('GET', 'base_uris', [
        //     'headers' => [
        //         'Access-Token' => $accessToken->getToken()
        //     ]
        // ]);
        $res = $client->request('GET', 'libraryDocuments', [
            'headers' => [
                'Access-Token' => $accessToken->getToken()
            ]
        ]);
        

        dd(json_decode($res->getBody(), true));

    }

    public function signature()
    {
        $provider = new \App\Echosign\Provider([
            'clientId'                => 'CBJCHBCAABAAZGBwHutkhmyBZGaOpFdVGKlyR5POt183',
            'clientSecret'            => 'q947CW0KP_BtZqi401EFobhA1QDRiu2f',
            'redirectUri'             => 'https://camp.dev:44300/refresh',
            'urlAuthorize'            => 'https://secure.na1.echosign.com/public/oauth',
            'scopes'                  => ['agreement_read', 'agreement_send', 'library_read'],
        ]);

        $accessToken = new \App\Echosign\AccessToken(json_decode('{"access_token":"3AAABLblqZhCJTbR4G8CYBChHEMPBP5EQZ3sDDd_e-X46-vXnrmm3scFZBcy7vuswfzJ4fxZPCo_UDra_mN-RwCDItHZxmVVo","refresh_token":"3AAABLblqZhB9gaUV8EvuGXWLtz4hJoTJIkyT3nvl4awMUSkvxzDVLt1ClxMpfHQXWRqSegjnLu4*","expires":1458580895,"api_access_point":"api.na1.echosign.com"}', true));

        if ($accessToken->hasExpired()) {
            $provider->setApiAccessPoint($accessToken->getApiBaseUri());

            $accessToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $accessToken->getRefreshToken(),
            ]);
        }

        $info = new \App\Echosign\DocumentCreationInfo;

        $info->setRecipient('mattdfloyd@gmail.com')
             ->setAttendeeName('Matt Floyd')
             ->setAttendeeType('Student')
             ->setChurchName('Passion City Church')
             ->setChurchLocation('Atlanta, GA');

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.na1.echosign.com/api/rest/v5/'
        ]);

        $res = $client->request('POST', 'agreements', [
            'headers' => [
                'Access-Token' => $accessToken->getToken(),
            ],
            'json' => [
                "documentCreationInfo" => $info
            ]
        ]);
        

        dd(json_decode($res->getBody(), true));

    }
}
