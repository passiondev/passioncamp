<?php

namespace App\Http\Controllers;

use App\Person;
use App\Http\Requests;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = $request->user();

            return $next($request);
        });
    }

    public function show(Request $request)
    {
        if (is_null($this->user->person)) {
            $this->user->person()->associate(Person::create())->save();
        }

        return view('profile.show')->withUser($this->user);
    }

    public function update()
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$this->user->id,
            'password' => 'confirmed'
        ]);

        $this->user->fill(request(['email']));

        if (request('password')) {
            $this->user->password = bcrypt(request('password'));
        }

        $this->user->save();

        $this->user->person()->update(request([
            'first_name',
            'last_name',
            'email',
        ]));

        session()->flash('success', 'Your profile has been updated.');

        return redirect('/');
    }
}
