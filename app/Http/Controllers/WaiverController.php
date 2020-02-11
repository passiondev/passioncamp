<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Jobs\Waiver\SendReminder;
use App\Waiver;

class WaiverController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function index()
    {
        $this->authorize('viewAny', Waiver::class);

        return view('waivers.index');
    }

    public function reminder(Waiver $waiver)
    {
        $this->authorize('remind', $waiver);

        if (! $waiver->canBeReminded()) {
            abort(403, 'This waiver cannot be reminded.');
        }

        SendReminder::dispatch($waiver);

        return request()->expectsJson()
            ? response($waiver, 201)
            : redirect()->back();
    }

    public function destroy(Waiver $waiver)
    {
        $this->authorize('delete', $waiver);

        $waiver->delete();

        return request()->expectsJson()
            ? response([], 204)
            : redirect()->back();
    }
}
