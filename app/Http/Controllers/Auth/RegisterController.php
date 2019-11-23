<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(User $user, $hash = null)
    {
        if ($user->isRegistered()) {
            return redirect('/');
        }

        if ($user->hash !== $hash && !request()->hasValidSignature()) {
            abort(401);
        }

        return view('auth.register', compact('user'));
    }

    public function register(User $user)
    {
        if (!request()->hasValidSignature()) {
            abort(401);
        }

        request()->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update([
            'password' => bcrypt(request()->input('password')),
        ]);

        auth()->login($user);

        return redirect('/');
    }
}
