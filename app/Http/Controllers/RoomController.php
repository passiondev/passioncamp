<?php

namespace App\Http\Controllers;

use App\Room;
use App\Filters\RoomFilters;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(RoomFilters $filters)
    {
        $rooms = Room::filter($filters)->with(['tickets.person', 'organization' => function ($q) {
            $q->withoutGlobalScopes();
        }, 'organization.church'])->orderByChurchName()->orderBy('id')->get();

        return request()->expectsJson()
            ? $rooms
            : view('room.index', compact('rooms'));
    }

    public function edit(Room $room)
    {
        $this->authorize($room);

        return view('room.edit', compact('room'));
    }

    public function update(Room $room)
    {
        $this->authorize($room);

        $room->update(
            auth()->user()->isSuperAdmin()
            ? request([
                'capacity', 'description', 'notes',
                'name', 'roomnumber', 'confirmation_number',
                'is_checked_in', 'is_key_received',
            ])
            : request(['capacity', 'description', 'notes'])
        );

        return redirect()->action('RoomingListController@index');
    }
}
