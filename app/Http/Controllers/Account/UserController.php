<?php

namespace App\Http\Controllers\Account;

use App\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function create()
    {
        return view('account.user.create', [
            'organization' => auth()->user()->organization
        ]);
    }

    public function store()
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        auth()->user()->organization->users()->create(request(['email']) + [
            'access' => 1,
            'person_id' => Person::create(request(['first_name', 'last_name']))->id,
        ]);

        return redirect()->action('Account\SettingsController@index');
    }
}
