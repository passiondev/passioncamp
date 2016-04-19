<?php

namespace App\Policies;

use App\User;
use App\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function makeStripePayments(User $user, Organization $organization)
    {
        return (bool) $organization->setting('stripe_access_token');
    }

    public function recordTransactions(User $user, Organization $organization = null)
    {
        if ($user->is_super_admin) {
            return true;
        }

        if (is_null($organization)) {
            $organization = auth()->user()->organization;
        }

        if (is_null($organization)) {
            return false;
        }

        return $organization->can_record_transactions;
    }
}
