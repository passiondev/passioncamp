<?php

namespace App\Policies;

use App\Note;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Note $note)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Note $note)
    {
        return $user->isSuperAdmin();
    }

    public function delete(User $user, Note $note)
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Note $note)
    {
        //
    }

    public function forceDelete(User $user, Note $note)
    {
        //
    }
}
