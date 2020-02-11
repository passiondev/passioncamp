<?php

namespace App\Policies;

use App\User;
use App\Notifications\OrganizationNotification;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

class DatabaseNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any organization notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the organization notification.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Notifications\DatabaseNotification  $databaseNotification
     * @return mixed
     */
    public function view(User $user, DatabaseNotification $databaseNotification)
    {
        if ($databaseNotification->type == OrganizationNotification::class) {
            return $user->organization->is($databaseNotification->notifiable);
        }
    }

    /**
     * Determine whether the user can create organization notifications.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the organization notification.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Notifications\DatabaseNotification  $databaseNotification
     * @return mixed
     */
    public function update(User $user, DatabaseNotification $databaseNotification)
    {
        if ($databaseNotification->type == OrganizationNotification::class) {
            return $user->organization->is($databaseNotification->notifiable);
        }
    }

    /**
     * Determine whether the user can delete the organization notification.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Notifications\DatabaseNotification  $databaseNotification
     * @return mixed
     */
    public function delete(User $user, DatabaseNotification $databaseNotification)
    {
        //
    }

    /**
     * Determine whether the user can restore the organization notification.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Notifications\DatabaseNotification  $databaseNotification
     * @return mixed
     */
    public function restore(User $user, DatabaseNotification $databaseNotification)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the organization notification.
     *
     * @param  \App\User  $user
     * @param  \Illuminate\Notifications\DatabaseNotification  $databaseNotification
     * @return mixed
     */
    public function forceDelete(User $user, DatabaseNotification $databaseNotification)
    {
        //
    }
}
