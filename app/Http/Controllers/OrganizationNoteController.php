<?php

namespace App\Http\Controllers;

use App\Organization;

class OrganizationNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Organization $organization)
    {
        $this->authorize('update', $organization);

        request()->validate([
            'body' => 'required',
        ]);

        $organization->addNote(request('body'));

        return redirect()->back()
            ->withSuccess('Note added.');
    }
}
