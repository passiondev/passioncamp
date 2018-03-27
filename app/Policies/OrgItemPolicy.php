<?php

namespace App\Policies;

use App\User;
use App\OrgItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrgItemPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function view(User $user, OrgItem $orgItem)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, OrgItem $orgItem)
    {
        //
    }

    public function delete(User $user, OrgItem $orgItem)
    {
        //
    }
}
