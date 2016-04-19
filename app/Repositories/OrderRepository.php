<?php

namespace App\Repositories;

use App\Order;

class OrderRepository
{
    public function forUser($user)
    {
        if ($user->is_super_admin) {
            return (new Order);
        }

        return $user->organization->orders();
    }
}