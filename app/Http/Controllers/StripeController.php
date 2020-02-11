<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function connect()
    {
        session_start();

        $provider = new \AdamPaterson\OAuth2\Client\Provider\Stripe([
            'clientId' => 'ca_8EBFdgpjjgyczC5KXY1m7IVAKTz2FxGV',
            'clientSecret' => config('settings.stripe.secret'),
            'redirectUri' => route('stripe.connect'),
        ]);

        if (! isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl(['scope' => 'read_write']);
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;

        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        }
        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);

        // Use this to interact with an API on the users behalf
        Auth::user()->organization->setting([
              'stripe_publishable_key' => $token->stripe_publishable_key,
              'stripe_user_id' => $token->stripe_user_id,
              'stripe_refresh_token' => $token->refresh_token,
              'stripe_access_token' => $token->access_token,
            ]);
    }
}
