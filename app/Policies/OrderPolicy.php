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
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function view(User $user, Order $order)
    {
        return $user->organization_id === $order->organization_id || $order->user_id == $user->id;
    }

    public function owner(User $user, Order $order)
    {
        return $user->organization_id === $order->organization_id || $order->user_id == $user->id;
    }

    public function adminOwner(User $user, Order $order)
    {
        return $user->isSuperAdmin() || ($user->isChurchAdmin() && $user->organization_id == $order->organization_id);
    }

    public function recordTransaction(User $user, Order $order)
    {
        if (! $order->organization->settings->exists('use_transactions')) {
            return false;
        }

        if ($user->id == $order->user_id) {
            return true;
        }

        if ($user->organization_id == $order->organization_id) {
            return true;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    public function addAttendees(User $user, Order $order)
    {
        return $this->isAdminOwner($user, $order);
    }

    public function editContact(User $user, Order $order)
    {
        return $this->isAdminOwner($user, $order);
    }

    private function isAdminOwner($user, $order)
    {
        return $user->isSuperAdmin() || ($user->isChurchAdmin() && $user->organization_id == $order->organization_id);
    }

    public function recordNotes($user, $order)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isChurchAdmin() && $order->organization_id == 8) {
            return true;
        }

        return false;
    }

    public function create(User $user, Order $order)
    {
        return $user->organization_id == $order->organization_id;
    }

    public function edit(User $user, Order $order)
    {
        return $this->update($user, $order);
    }

    public function update(User $user, Order $order)
    {
        return $user->organization_id == $order->organization_id;
    }
}
