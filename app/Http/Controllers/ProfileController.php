<?php

namespace App\Http\Controllers;

use App\Person;
use App\Http\Requests;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = request()->user();

        if (is_null($user->person)) {
            $user->person()->associate(Person::create())->save();
        }

        return view('profile.show')->withUser($user);
    }

    public function update()
    {
        $user = request()->user();

        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'password' => 'confirmed'
        ]);

        $user->fill(request(['email']));

        if (request('password')) {
            $user->password = bcrypt(request('password'));
        }

        $user->save();

        $user->person()->update(request([
            'first_name',
            'last_name',
            'email',
        ]));

        session()->flash('success', 'Your profile has been updated.');

        return redirect('/');
    }
}
