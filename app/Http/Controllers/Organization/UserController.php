<?php

namespace App\Http\Controllers\Organization;

use App\User;
use App\Person;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function create(Organization $organization)
    {
        return view('admin.organization.user.create')->withOrganization($organization);
    }

    public function store(Request $request, Organization $organization)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:user,email',
        ]);

        $user = $this->users->create($request->all());

        $organization->authUsers()->save($user);

        $this->sendAccountCreationEmail($user);

        return redirect()->route('admin.organization.show', $organization)->with('success', 'User added.');
    }

    public function sendAccountCreationEmail(User $user)
    {
        $client = new PostmarkClient("e717520f-5d7b-4e98-a7fe-a65da0630435");
        $client->sendEmailWithTemplate('passioncamp@268generation.com', $user->email, 574163, [
            'name' => $user->person->first_name,
            'link' => route('complete.registration', [$user, $user->hash])
        ]);
    }
}
