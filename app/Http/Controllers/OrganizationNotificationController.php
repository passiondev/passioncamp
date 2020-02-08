<?php

namespace App\Http\Controllers;

use App\Notifications\OrganizationNotification;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class OrganizationNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:super']);
    }

    public function index()
    {
        $notifications = DatabaseNotification::query()
            ->where('type', OrganizationNotification::class)
            ->latest()
            ->paginate(5);

        $organizations = Organization::active()->orderByChurchName()->get();

        return view('organization-notifications.index', compact('notifications', 'organizations'));
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

        return redirect()->back()->with('success', 'Notification sent.');
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted.');
    }
}
