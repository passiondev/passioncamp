<?php

namespace App\Repositories;

use App\Order;

class OrderRepository
{
    public function forUser($user)
    {
        if ($user->isSuperAdmin()) {
            return (new Order);
        }

        return $user->organization->orders();
    }
}
