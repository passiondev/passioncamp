<?php

namespace App\Policies;

use App\User;
use App\Waiver;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaiverPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (config('passioncamp.waiver_test_mode')) {
            return false;
        }
    }

    /**
     * Determine whether the user can view the waiver.
     *
     * @param  \App\User  $user
     * @param  \App\Waiver  $waiver
     * @return mixed
     */
    public function view(User $user, Waiver $waiver)
    {
        return $user->organization_id == $waiver->ticket->order->organization_id;
    }

    /**
     * Determine whether the user can create waivers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the waiver.
     *
     * @param  \App\User  $user
     * @param  \App\Waiver  $waiver
     * @return mixed
     */
    public function update(User $user, Waiver $waiver)
    {
        return $this->view($user, $waiver);
    }

    /**
     * Determine whether the user can delete the waiver.
     *
     * @param  \App\User  $user
     * @param  \App\Waiver  $waiver
     * @return mixed
     */
    public function delete(User $user, Waiver $waiver)
    {
        return $this->view($user, $waiver);
    }
}
