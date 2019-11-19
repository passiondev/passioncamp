<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Http\Middleware\Authenticate;

class OrganizationNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
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
