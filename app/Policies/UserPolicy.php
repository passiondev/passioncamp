<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function update(User $authUser, User $user)
    {
        // if ($authUser->isChurchAdmin()) {
        //     return $authUser->organization_id == $user->organization_id;
        // }

        return $authUser->isChurchAdmin() || $authUser->id == $user->id;
    }

    public function impersonate(User $authUser, User $user)
    {
        if ($authUser->id == $user->id) {
            return false;
        }
    }
}
