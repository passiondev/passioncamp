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

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->users->getAdminUsers();

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

        $organization = null;

        if (! auth()->user()->is_super_admin) {
            $organization = auth()->user()->organization;
        }

        if (is_null($organization)) {
            $organization = Organization::findOrFail($request->organization);
        }

        $user = $this->users->create($request->all());

        $organization->authUsers()->save($user);

        $this->sendAccountCreationEmail($user);

        if (auth()->user()->is_super_admin) {
            return redirect()->route('organization.settings.index', $organization);
        }

        return redirect()->route('account.settings');
    }

    public function sendAccountCreationEmail(User $user)
    {
        // Mail::send('auth.emails.register', compact('user'), function ($m) use ($user) {
        //     $m->subject('Create Your Account');
        //     $m->to($user->email);
        // });
        $client = new PostmarkClient("e717520f-5d7b-4e98-a7fe-a65da0630435");
        $client->sendEmailWithTemplate('passioncamp@268generation.com', $user->email, 574163, [
            'name' => $user->person->first_name,
            'link' => route('complete.registration', [$user, $user->hash])
        ]);
    }

}
