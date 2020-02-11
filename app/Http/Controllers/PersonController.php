<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Person;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function edit(Person $person)
    {
        request()->intended(url()->previous());

        $this->authorize($person);

        return view('person.edit', compact('person'));
    }

    public function update(Person $person)
    {
        request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        try {
            $user = User::whereEmail(request('email'))->firstOrFail();

            $this->authorize('update', $user);

            $user->person->update(request(['first_name', 'last_name', 'email', 'phone']));
        } catch (ModelNotFoundException $e) {
            $person->update(request(['first_name', 'last_name', 'email', 'phone']));
            $person->user->update(request(['email']));
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->intended()->withSuccess('Person updated.');
    }
}
