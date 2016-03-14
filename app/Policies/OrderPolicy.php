<?php

namespace App\Policies;

use App\User;
use App\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->is_super_admin) {
            return true;
        }
    }

    public function owner(User $user, Order $order)
    {
        return $user->organization_id === $order->organization_id;
    }
}
