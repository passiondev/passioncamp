<?php

namespace App\Http\Controllers;

use App\Filters\RoomFilters;
use App\Hotel;
use App\Http\Middleware\Authenticate;
use App\Organization;
use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
    }

    public function index(RoomFilters $filters)
    {
        $rooms = Room::filter($filters)
            ->orderByChurchName()
            ->orderBy('id')
            ->with([
                'tickets.person',
                'organization',
                'organization.church',
            ])
            ->when($filters->hasFilters(), function ($q) {
                return $q->get();
            }, function ($q) {
                return $q->paginate();
            });

        $organizations = Organization::withoutGlobalScopes()
            ->with('church')
            ->orderByChurchName()
            ->get();

        $hotels = Hotel::orderBy('name')->get();

        return request()->expectsJson()
            ? $rooms
            : view('room.index', compact('rooms', 'organizations', 'hotels'));
    }

    public function edit(Request $request, Room $room)
    {
        request()->intended(url()->previous());

        $this->authorize($room);

        $maxCapacity = $room->organization->slug == 'pcc' ? 5 : 4;

        if ($request->user()->isSuperAdmin()) {
            $maxCapacity = 5;
        }

        return view('room.edit', compact('room', 'maxCapacity'));
    }

    public function update(Room $room)
    {
        $this->authorize($room);

        $room->update(
            auth()->user()->isSuperAdmin()
            ? request([
                'capacity', 'description',
                'name', 'roomnumber', 'confirmation_number',
                'is_checked_in', 'is_key_received',
                'adp_required', 'king_preferred',
            ])
            : request(['capacity', 'description', 'adp_required', 'king_preferred'])
        );

        return redirect()->intended(action('RoomingListController@index'));
    }

    public function checkin(Room $room)
    {
        $room->checkin();

        return request()->expectsJson()
            ? response()->json($room, 204)
            : redirect()->back();
    }

    public function keyReceived(Room $room)
    {
        $room->keyReceived();

        return request()->expectsJson()
            ? response()->json($room, 204)
            : redirect()->back();
    }
}
