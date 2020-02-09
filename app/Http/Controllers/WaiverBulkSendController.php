<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WaiverBulkSendController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'organization' => ['required'],
        ]);

        $organization = Organization::findOrFail($request->input('organization'));

        $this->authorize('update', $organization);

        $waiversSent = 0;
        $organization->attendees()->each(function ($attendee) use (&$waiversSent) {
            if ($attendee->waiver) {
                return;
            }

            $attendee->createWaiver();

            $waiversSent++;
        });

        return redirect()->back()
            ->with('success', vsprintf('%d %s sent.', [$waiversSent, Str::plural('waiver', $waiversSent)]));
    }
}
