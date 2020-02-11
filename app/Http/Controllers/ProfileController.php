<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function show()
    {
        $user = request()->user();

        return view('profile.show', compact('user'));
    }

    public function update()
    {
        $user = request()->user();

        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'email' => request()->input('email'),
            'person' => [
                'first_name' => request()->input('first_name'),
                'last_name' => request()->input('last_name'),
                'email' => request()->input('email'),
            ],
        ]);

        return redirect('/')->withSuccess('Your profile has been updated.');
    }
}
