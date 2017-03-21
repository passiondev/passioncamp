<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function edit(User $user)
    {
        $this->authorize($user);

        return view('user.edit')->withUser($user);
    }

    public function update(User $user)
    {
        $this->authorize($user);

        $this->validate(request(), [
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

        $user->update(request(['email']));
        $user->person->update(request(['first_name', 'last_name']));

        return auth()->user()->isSuperAdmin()
             ? redirect()->action('Super\OrganizationController@show', $user->organization)
             : redirect()->action('Account\SettingsController@index');
    }
}
