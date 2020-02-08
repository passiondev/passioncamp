<?php

namespace App\Http\Controllers;

use App\Notifications\OrganizationNotification;
use App\Organization;
use Illuminate\Http\Request;

class OrganizationNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:super']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
        ]);

        $organizations = $request->filled('organization')
            ? Organization::whereKey($request->input('organization'))->get()
            : Organization::active()->get();

        $organizations->each->notify(
            new OrganizationNotification($request->input('subject'))
        );
    }
}
