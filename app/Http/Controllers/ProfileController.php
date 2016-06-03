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
        $this->user = auth()->user();
    }

    public function show(Request $request)
    {
        $form_data = [
            'first_name' => $this->user->person->first_name ?: '', 
            'last_name' => $this->user->person->last_name ?: '', 
            'email' => $this->user->email ?: ''
        ];

        return view('profile.show', compact('form_data'))->withUser($this->user);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:user,email,'.$this->user->id,
            'password' => 'confirmed'
        ]);

        if (is_null($this->user->person)) {
            $person = Person::create();
            $this->user->person()->associate($person);
        }

        $this->user->fill($request->only('email'));

        if (strlen($request->password)) {
            $this->user->password = bcrypt($request->password);
        }

        $this->user->save();
        $this->user->person()->update($request->only([
            'first_name',
            'last_name',
            'email',
        ]));

        return redirect()->intended('profile');
    }
}
