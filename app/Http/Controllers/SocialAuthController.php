<?php

namespace App\Http\Controllers;

use App\User;
use App\SocialAccount;
use Laravel\Socialite\Facades\Socialite;
use App\Exceptions\SocialAccountAlreadyRegistered;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SocialAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('disconnect');
    }

    public function redirect($provider)
    {
        return Socialite::redirectUrl(action('SocialAuthController@callback', 'google'))->driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $user = $this->createOrGetUser($provider, auth('social')->check() ? auth('social')->user() : auth()->user());
        } catch (SocialAccountAlreadyRegistered $e) {
            return redirect()->back()->withError($e->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withError(sprintf('An account cannot be found with your %s email address.', ucfirst($provider)));
        } finally {
            auth('social')->logout();
        }

        auth()->login($user);

        return redirect()->back();
    }

    public function disconnect($provider)
    {
        SocialAccount::whereProvider($provider)
            ->whereUserId(auth()->user()->id)
            ->delete();

        return redirect()->back()->withSuccess(sprintf('%s account has been disconnected.', ucfirst($provider)));
    }

    private function createOrGetUser($provider, User $authUser = null)
    {
        $providerUser = Socialite::driver($provider)->user();

        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account && $authUser && $account->user_id != $authUser->id) {
            throw SocialAccountAlreadyRegistered::for($provider);
        }

        if (! $account && $authUser) {
            $account = SocialAccount::create([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider,
                'user_id' => $authUser->id,
            ]);
        }

        if (! $account) {
            $user = User::whereEmail($providerUser->getEmail())->whereNotNull('access')->firstOrFail();

            $account = SocialAccount::create([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $provider,
                'user_id' => $user->id,
            ]);
        }

        return $account->user;
    }
}
