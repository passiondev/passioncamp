<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class OrganizationNoteController extends Controller
{
    public function store(Organization $organization)
    {
        $this->authorize('edit', $organization);

        $this->validate(request(), [
            'body' => 'required',
        ]);

        $organization->addNote(request('body'));

        return redirect()->back()->withSuccess('Note added.');
    }
}
