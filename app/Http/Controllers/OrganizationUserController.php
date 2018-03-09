<?php

namespace App\Http\Controllers;

use App\User;
use App\Person;
use App\AccountUser;
use App\Organization;
use App\Mail\AccountUserCreated;
use Illuminate\Support\Facades\Mail;

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

        $user = $organization->users()->create(request(['email']) + [
            'access' => 1,
            'person_id' => Person::create(request(['first_name', 'last_name']))->id,
        ]);

        Mail::to($user)->send(new AccountUserCreated($user));

        return redirect()->action('OrganizationController@show', $organization);
    }

    public function destroy(Organization $organization, User $user)
    {
        $this->authorize('destroy', new AccountUser($organization, $user));

        $user->organization()->dissociate()->save();

        return redirect()->back();
    }
}
