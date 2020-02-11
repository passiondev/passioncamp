<?php

namespace App\Http\Controllers\Account;

use App\AccountUser;
use App\Http\Controllers\Controller;
use App\Http\Middleware\VerifyUserIsChurchAdmin;
use App\Mail\AccountUserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(VerifyUserIsChurchAdmin::class);
    }

    public function create()
    {
        return view('account.user.create', [
            'organization' => auth()->user()->organization,
        ]);
    }

    public function store()
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        $user = auth()->user()->organization->users()->create([
            'email' => request('email'),
            'access' => 1,
            'person' => request()->only([
                'first_name',
                'last_name',
            ]),
        ]);

        Mail::to($user)->send(new AccountUserCreated($user));

        return redirect()->route('account.settings');
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', new AccountUser(auth()->user()->organization, $user));

        $user->organization()->dissociate()->save();

        return redirect()->back();
    }
}
