<?php

namespace App\Http\Controllers;

use App\AccountUser;
use App\Mail\AccountUserCreated;
use App\Organization;
use App\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Mail;

class OrganizationUserController extends Controller
{
    public function __construct()
    {
        $this->middleware([Authenticate::class, 'can:super']);
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

        $user->update(['access' => 1]);

        if (auth()->user()->isChurchAdmin() && $user->wasRecentlyCreated) {
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
