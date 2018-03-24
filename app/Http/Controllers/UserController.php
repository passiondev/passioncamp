<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(User $user)
    {
        $this->authorize($user);

        return view('user.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize($user);

        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
        ]);

        if (auth()->user()->isSuperAdmin()) {
            if (request('organization') == 'ADMIN') {
                $user->access = 100;
                $user->organization()->dissociate();
            } else {
                $user->access = 1;
                $user->organization()->associate(request('organization'));
            }
        }

        $user->update([
            'email' => request()->input('email'),
            'person'=> [
                'first_name' => request()->input('first_name'),
                'last_name' => request()->input('last_name'),
            ],
        ]);

        if (auth()->user()->isSuperAdmin()) {
            return $user->organization
                ? redirect()->action('OrganizationController@show', $user->organization)
                : redirect()->route('admin.users.index');
        }

        return redirect()->action('Account\SettingsController@index');
    }
}
