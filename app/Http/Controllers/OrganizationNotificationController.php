<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
use App\Notifications\OrganizationNotification;

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

        Organization::active()->get()->each->notify(new OrganizationNotification($request->input('subject')));
    }
}
