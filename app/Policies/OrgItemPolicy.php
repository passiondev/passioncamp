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

    /**
     * Determine whether the user can view the orgItem.
     *
     * @param  \App\User  $user
     * @param  \App\OrgItem  $orgItem
     * @return mixed
     */
    public function view(User $user, OrgItem $orgItem)
    {
        //
    }

    /**
     * Determine whether the user can create orgItems.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the orgItem.
     *
     * @param  \App\User  $user
     * @param  \App\OrgItem  $orgItem
     * @return mixed
     */
    public function update(User $user, OrgItem $orgItem)
    {
        //
    }

    /**
     * Determine whether the user can delete the orgItem.
     *
     * @param  \App\User  $user
     * @param  \App\OrgItem  $orgItem
     * @return mixed
     */
    public function delete(User $user, OrgItem $orgItem)
    {
        //
    }
}
