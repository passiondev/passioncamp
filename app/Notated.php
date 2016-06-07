<?php

namespace App;

use App\Note;
use Illuminate\Support\Facades\Auth;

trait Notated
{
    public function notes()
    {
        return $this->morphMany(Note::class, 'notated');
    }

    public function addNote($body)
    {
        $note = new Note;
        $note->body = $body;

        if (Auth::user()) {
            $note->author()->associate(Auth::user());
        }

        $this->notes()->save($note);

        return $note;
    }
}
