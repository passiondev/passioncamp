<?php

namespace App\Policies;

use App\User;
use App\TransactionSplit;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionSplitPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
    }

    public function view(User $user, TransactionSplit $transactionSplit)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, TransactionSplit $transactionSplit)
    {
    }

    public function delete(User $user, TransactionSplit $transactionSplit)
    {
    }

    public function restore(User $user, TransactionSplit $transactionSplit)
    {
    }

    public function forceDelete(User $user, TransactionSplit $transactionSplit)
    {
    }
}
