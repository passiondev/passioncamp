<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function showRegistrationForm(User $user, $hash)
    {
        if ($user->is_registered) {
            return redirect('/');
        }

        if ($user->hash !== $hash) {
            abort(403, 'Not authorized.');
        }

        return view('auth.register', compact('user'));
    }

    public function register(Request $request, User $user, $hash)
    {
        if ($user->hash !== $hash) {
            abort(403, 'Not authorized.');
        }

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        $user->password = bcrypt($request->password);
        $user->save();

        Auth::login($user);

        return redirect($this->redirectPath());
    }

    public function registerWithSocial($provider, User $user, $hash)
    {
        if ($user->hash !== $hash) {
            abort(403, 'Not authorized.');
        }

        auth('social')->login($user);

        return Socialite::driver($provider)->redirectUrl(action('SocialAuthController@callback', [$provider]))->redirect();
    }
}
