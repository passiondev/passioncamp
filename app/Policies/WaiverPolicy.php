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

    public function viewAny(User $user)
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, Waiver $waiver)
    {
        return $user->organization_id == $waiver->ticket->order->organization_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Waiver $waiver)
    {
        return false;
    }

    public function delete(User $user, Waiver $waiver)
    {
        return true;
    }

    public function remind(User $user, Waiver $waiver)
    {
        return true;
    }
}
