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
            'first_name' => array_get($data, 'first_name'),
            'last_name' => array_get($data, 'last_name'),
            'email' => array_get($data, 'email'),
            'street' => array_get($data, 'street'),
            'city' => array_get($data, 'city'),
            'state' => array_get($data, 'state'),
            'zip' => array_get($data, 'zip'),
            'country' => array_get($data, 'country'),
        ])->save();

        $user->forceFill([
            'email' => array_get($data, 'email'),
            'access' => $access
        ])->person()->associate($person)->save();

        // event(new UserCreated($user));

        return $user;
    }

    public function update(User $user, array $data, $access)
    {
        $user->forceFill([
            'email' => array_get($data, 'email'),
            'access' => $access
        ])->save();

        $user->person->forceFill([
            'first_name' => array_get($data, 'first_name'),
            'last_name' => array_get($data, 'last_name'),
            'email' => array_get($data, 'email'),
            'street' => array_get($data, 'street'),
            'city' => array_get($data, 'city'),
            'state' => array_get($data, 'state'),
            'zip' => array_get($data, 'zip'),
            'country' => array_get($data, 'country'),
        ])->save();

        return $user;
    }

    public function getAdminUsers()
    {
        return User::whereNotNull('email')->get();
    }
}
