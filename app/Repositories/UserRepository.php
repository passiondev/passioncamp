<?php

namespace App\Repositories;

use App\User;
use App\Person;

class UserRepository
{
    public function create(array $data, $access = 1)
    {
        $user = new User;
        $person = new Person;

        $person->forceFill([
                array_get($data, 'first_name'),
                array_get($data, 'last_name'),
                array_get($data, 'email'),
                array_get($data, 'street'),
                array_get($data, 'city'),
                array_get($data, 'state'),
                array_get($data, 'zip'),
                array_get($data, 'country'),
        ])->save();

        $user->forceFill([
            'email' => array_get($data, 'email'),
            'password' => bcrypt(array_get($data, 'password')),
            'access' => $access
        ])->person()->associate($person);

        event(new UserCreated($user));

        return $user;
    }

    public function getAdminUsers()
    {
        return User::where('access', '>=', '1')->get();
    }
}