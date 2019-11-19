<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\EmailLogin;
use Illuminate\Http\Request;
use App\Auth\MagicAuthentication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MagicLinkLoginController extends Controller
{
    public $redirectTo = '/dashboard';

    public function show()
    {
        return view('auth.magic-login');
    }

    public function sendToken(Request $request, MagicAuthentication $auth)
    {
        $request->validate([
            'email' => ['bail', 'required', 'email', 'exists:users', function ($attribute, $value, $fail) {
                if (!User::where('email', $value)->first()->canUseMagicLink()) {
                    return $fail('The user is invalid.');
                }
            }],
        ]);

        $auth->requestLink();

        return redirect()->route('magic.login')->with('magic-link-sent', $request->input('email'));
    }

    public function authenticate(Request $request, $token)
    {
        try {
            $token = EmailLogin::where('token', $token)->firstOrFail();
            $token->validateRequest($request);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('magic.login')->with('error', 'This Magic Link is no longer valid.');
        } catch (\Exception $e) {
            return redirect()->route('magic.login')->with('error', $e->getMessage());
        }

        Auth::login($token->user, $request->remember);

        return redirect('/dashboard');
    }
}
