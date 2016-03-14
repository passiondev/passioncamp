<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    public function owner(User $user, $item)
    {
        return Gate::check('owner', $item->order);
    }
}
