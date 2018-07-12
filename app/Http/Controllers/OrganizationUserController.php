<?php

namespace App\Http\Controllers;

use App\User;
use App\AccountUser;
use App\Organization;
use App\Mail\AccountUserCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\VerifyUserIsSuperAdmin;

class OrganizationUserController extends Controller
{
    public function __construct()
    {
        $this->middleware([Authenticate::class, VerifyUserIsSuperAdmin::class]);
    }

    public function create(Organization $organization)
    {
        return view('admin.organization.user.create', compact('organization'));
    }

    public function store(Organization $organization)
    {
        request()->validate([
            'email' => 'required',
        ]);

        $user = $organization->users()->save(
            User::firstOrCreate(['email' => request('email')])
        );

        if ($user->wasRecentlyCreated) {
            Mail::to($user)->send(new AccountUserCreated($user));
        }

        return redirect()->route('admin.organizations.show', $organization);
    }

    public function destroy(Organization $organization, User $user)
    {
        $this->authorize('destroy', new AccountUser($organization, $user));

        $user->organization()->dissociate()->save();

        return redirect()->back();
    }
}
