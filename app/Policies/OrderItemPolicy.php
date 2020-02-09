<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class OrderItemPolicy
{
    use HandlesAuthorization;

    public function owner(User $user, $item)
    {
        return Gate::check('owner', $item->order);
    }

    public function edit(User $user, $item)
    {
        return $this->update($user, $item);
    }

    public function update(User $user, $item)
    {
        if ($item->isOrganizationItem()) {
            return $user->isSuperAdmin();
        }

        return true;
    }
}
