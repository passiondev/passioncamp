<?php

namespace App\Http\Controllers\Organization;

use App\User;
use App\Person;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function create(Organization $organization)
    {
        return view('admin.organization.user.create')->withOrganization($organization);
    }

    public function store(Request $request, Organization $organization)
    {
        $person = Person::create($request->only('first_name', 'last_name', 'email'));

        $user = new User;
        $user->organization()->associate($organization);
        $user->person()->associate($person);
        $user->username = $request->email;
        $user->access = 1;
        $user->save();

        return redirect()->route('admin.organization.show', $organization)->with('success', 'User added.');
    }
}
