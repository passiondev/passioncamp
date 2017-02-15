<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/account/settings';

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function reset(Request $request, User $user)
    {
        $response = Password::broker(null)->sendResetLink(
            ['email' => $user->email],
            $this->resetEmailBuilder()
        );

        return redirect()->back()->with('status', trans($response));
    }
}
