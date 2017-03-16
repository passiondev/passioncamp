<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use App\Person;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use Postmark\PostmarkClient;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
        $this->middleware('super')->only('index');
        $this->middleware('admin')->except('index');
    }

    public function index()
    {
        $users = User::with('person', 'organization.orders', 'organization.church')
            ->whereNotNull('email')
            ->where(function ($q) {
                $q->whereNotNull('organization_id')->orWhere('access', 100);
            })
            ->paginate();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create', ['user' => new User]);
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        $user = new User(request(['email', 'flags']));

        if (auth()->user()->isSuperAdmin()) {
            if (request('organization') == 'ADMIN') {
                $user->access = 100;
            } else {
                $user->access = 1;
                $user->organization()->associate(request('organization'));
            }
        }

        $user->person()->associate(Person::create(request(['first_name', 'last_name'])));
        $user->save();

        // $this->sendAccountCreationEmail($user);

        if ($user->isSuperAdmin()) {
            return redirect()->route('user.index');
        }

        if (auth()->user()->isSuperAdmin()) {
            return redirect()->route('organization.settings.index', $user->organization);
        }

        return redirect()->route('account.settings');
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
            if (request('organization') == 'ADMIN') {
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
        /**
         * TODO
         */
        // Mail::send('auth.emails.register', compact('user'), function ($m) use ($user) {
        //     $m->subject('Create Your Account');
        //     $m->to($user->email);
        // });
    }
}
