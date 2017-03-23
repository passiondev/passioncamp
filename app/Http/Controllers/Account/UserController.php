<?php

namespace App\Http\Controllers\Account;

use App\Person;
use Illuminate\Http\Request;
use App\Mail\AccountUserCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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

        $user = auth()->user()->organization->users()->create(request(['email']) + [
            'access' => 1,
            'person_id' => Person::create(request(['first_name', 'last_name']))->id,
        ]);

        Mail::to($user)->send(new AccountUserCreated($user));

        return redirect()->action('Account\SettingsController@index');
    }
}
