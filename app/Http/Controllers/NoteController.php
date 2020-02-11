<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
        $this->authorizeResource(Note::class);
    }

    public function update(Request $request, Note $note)
    {
        $validatedData = $request->validate([
            'body' => 'required',
        ]);

        $note->update($validatedData);

        return redirect()->back();
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return redirect()->back();
    }
}
