<?php

namespace App\Http\Controllers\Super;

use App\User;
use App\Person;
use App\Mail\AccountUserCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index()
    {
        $users = User::with('person', 'organization.orders', 'organization.church')
            ->whereNotNull('email')
            ->whereAccess(100)
            ->paginate();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create', ['user' => new User()]);
    }

    public function store()
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        $user = User::create([
            'email' => request('email'),
            'access' => 100,
            'person_id' => Person::create(request(['first_name', 'last_name']))->id,
        ]);

        Mail::to($user)->send(new AccountUserCreated($user));

        return redirect()->action('Super\UserController@index')->withSuccess('Admin added.');
    }

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
            if ('ADMIN' == request('organization')) {
                $user->access = 100;
                $user->organization()->dissociate();
            } else {
                $user->access = 1;
                $user->organization()->associate(request('organization'));
            }
        }

        $user->update(request(['email', 'flags']));
        $user->person()->update(request(['first_name', 'last_name']));

        return redirect()->route(auth()->user()->isSuperAdmin() ? 'user.index' : 'account.settings');
    }

    public function sendAccountCreationEmail(User $user)
    {
        /*
         * TODO
         */
        // Mail::send('auth.emails.register', compact('user'), function ($m) use ($user) {
        //     $m->subject('Create Your Account');
        //     $m->to($user->email);
        // });
    }
}
