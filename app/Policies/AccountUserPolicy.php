<?php

namespace App\Policies;

use App\User;
use App\AccountUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountUserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function create(User $user, AccountUser $accountUser)
    {
        return $user->organization->is($accountUser->organization);
    }

    public function destroy(User $user, AccountUser $accountUser)
    {
        return $user->isNot($accountUser->user)
            && $this->create($user, $accountUser);
    }
}
