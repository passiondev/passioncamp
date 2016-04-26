<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
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
    }

    public function index()
    {
        $users = $this->users->getAdminUsers();
        $users->load('person', 'organization.orders');

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:user,email',
        ]);

        $user = $this->users->create(
            $request->all(),
            Auth::user()->is_super_admin && $request->organization == 'ADMIN' ? 100 : 1
        );

        if (! $user->is_super_admin) {
            $organization = null;

            if (! auth()->user()->is_super_admin) {
                $organization = auth()->user()->organization;
            }

            if (is_null($organization)) {
                $organization = Organization::findOrFail($request->organization);
            }

            $organization->authUsers()->save($user);
        }

        $this->sendAccountCreationEmail($user);

        if ($user->is_super_admin) {
            return redirect()->route('user.index');
        }

        if (auth()->user()->is_super_admin) {
            return redirect()->route('organization.settings.index', $organization);
        }

        return redirect()->route('account.settings');
    }

    public function edit(User $user)
    {
        $this->authorize($user);

        $user_data = [
            'organization' => $user->is_super_admin ? 'ADMIN' : $user->organization_id,
            'first_name' => $user->person->first_name,
            'last_name' => $user->person->last_name,
            'email' => $user->person->email,
        ];

        return view('user.edit', compact('user_data'))->withUser($user);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize($user);

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:user,email,'.$user->id,
        ]);

        $this->users->update($user, $request->all(), Auth::user()->is_super_admin && $request->organization == 'ADMIN' ? 100 : 1);

        return redirect()->route(Auth::user()->is_super_admin ? 'user.index' : 'account.settings');
    }

    public function sendAccountCreationEmail(User $user)
    {
        Mail::send('auth.emails.register', compact('user'), function ($m) use ($user) {
            $m->subject('Create Your Account');
            $m->to($user->email);
        });
    }
}
