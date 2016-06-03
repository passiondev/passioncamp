<?php

namespace App\Http\Controllers\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function store(Request $request, Organization $organization)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $note = $organization->addNote($request->body);

        return redirect()->route('admin.organization.show', $organization);
    }
}
