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

    /**
     * Determine whether the user can view the transaction split.
     *
     * @param \App\User             $user
     * @param \App\TransactionSplit $transactionSplit
     *
     * @return mixed
     */
    public function view(User $user, TransactionSplit $transactionSplit)
    {
    }

    /**
     * Determine whether the user can create transaction splits.
     *
     * @param \App\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the transaction split.
     *
     * @param \App\User             $user
     * @param \App\TransactionSplit $transactionSplit
     *
     * @return mixed
     */
    public function update(User $user, TransactionSplit $transactionSplit)
    {
    }

    /**
     * Determine whether the user can delete the transaction split.
     *
     * @param \App\User             $user
     * @param \App\TransactionSplit $transactionSplit
     *
     * @return mixed
     */
    public function delete(User $user, TransactionSplit $transactionSplit)
    {
    }

    /**
     * Determine whether the user can restore the transaction split.
     *
     * @param \App\User             $user
     * @param \App\TransactionSplit $transactionSplit
     *
     * @return mixed
     */
    public function restore(User $user, TransactionSplit $transactionSplit)
    {
    }

    /**
     * Determine whether the user can permanently delete the transaction split.
     *
     * @param \App\User             $user
     * @param \App\TransactionSplit $transactionSplit
     *
     * @return mixed
     */
    public function forceDelete(User $user, TransactionSplit $transactionSplit)
    {
    }
}
