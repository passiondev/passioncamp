<?php

namespace App\Policies;

use App\Hotel;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HotelPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        return $user->isSuperAdmin();
    }

    public function viewAny(User $user)
    {
    }

    public function view(User $user, Hotel $hotel)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, Hotel $hotel)
    {
    }

    public function delete(User $user, Hotel $hotel)
    {
    }

    public function restore(User $user, Hotel $hotel)
    {
    }

    public function forceDelete(User $user, Hotel $hotel)
    {
    }
}
