<?php

namespace App\Policies;

use App\Person;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function update(User $user, Person $person)
    {
        if ($user->isChurchAdmin()) {
            return $user->organization_id == $person->user->orders->first()->organization_id;
        }

        return $user->id == $person->user_id;
    }
}
