<?php

namespace App\Http\Controllers;

use App\User;
use App\Person;
use App\Organization;

class OrganizationUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function create(Organization $organization)
    {
        return view('super.organization-user.create', compact('organization') + ['user' => new User]);
    }

    public function store(Organization $organization)
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        $organization->users()->create(request(['email']) + [
            'access' => 1,
            'person_id' => Person::create(request(['first_name', 'last_name']))->id,
        ]);

        return redirect()->action('OrganizationController@show', $organization);
    }
}
