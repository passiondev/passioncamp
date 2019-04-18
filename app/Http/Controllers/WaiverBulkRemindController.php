<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Jobs\Waiver\SendReminder;

class WaiverBulkRemindController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'organization' => ['required'],
        ]);

        $organization = Organization::findOrFail($request->input('organization'));

        $remindersSent = 0;
        $organization->attendees()->each(function ($attendee) use (&$remindersSent) {
            if (! $attendee->waiver) {
                return;
            }

            if (! $attendee->waiver->canBeReminded()) {
                return;
            }

            $attendee->waiver->touch(); // to prevent reminder from being triggered again

            SendReminder::dispatch($attendee->waiver);

            $remindersSent++;
        });

        return redirect()->back()
            ->with('success', vsprintf("%d %s sent.", [$remindersSent, str_plural('reminder', $remindersSent)]));
    }
}
