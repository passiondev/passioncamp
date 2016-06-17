<?php

namespace App\Http\Controllers;

use Gate;
use App\Room;
use App\Hotel;
use Exception;
use App\Ticket;
use App\Organization;
use Illuminate\Http\Request;
use App\Repositories\RoomRepository;
use App\Repositories\TicketRepository;

class RoomingListController extends Controller
{
    private $rooms;

    public function __construct(RoomRepository $rooms)
    {
        $this->rooms = $rooms;

        $this->middleware('admin');
    }

    public function index()
    {
        $unassigned = Ticket::active()->forUser()->unassigned()->with('person')->orderBy('agegroup')->get()->unassigendSort();
        $rooms = Room::forUser()->with('tickets.person', 'organization.church')->get();

        return view('roominglist.index', compact('unassigned', 'rooms'));
    }

    public function show(Request $request, Room $room)
    {
        $this->authorize('owner', $room);

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'view' => view('roominglist.partials.room')->withRoom($room->fresh('tickets'))->render()
            ])
            : redirect()->route('roominglist.index');
    }

    public function assign(Request $request, Ticket $ticket, Room $room)
    {
        $this->authorize('owner', $ticket);
        $this->authorize('owner', $room);

        try {
            $this->rooms->assign($room, $ticket);
        } catch (Exception $e) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json([
                    'message' => $e->getMessage(), 
                    'view' => view('roominglist.partials.room')->withRoom($room->fresh('tickets'))->render()
                ], 400)
                : abort(400, $e->getMessage());
        }

        return $request->ajax() || $request->wantsJson()
            ? response()->json([
                'view' => view('roominglist.partials.room')->withRoom($room->fresh('tickets'))->render()
            ])
            : redirect()->route('roominglist.index');
    }

    public function unassign(Request $request, Ticket $ticket)
    {
        $this->authorize('owner', $ticket);

        $ticket->room_id = null;
        $ticket->save();
    }

    public function edit(Room $room)
    {
        $this->authorize('owner', $room);
        $hotelOptions = Hotel::all()->sortBy('name')->keyBy('id')->map(function ($hotel) { return $hotel->name; });

        return view('roominglist.edit', compact('room', 'hotelOptions'));
    }

    public function update(Request $request, Room $room)
    {
        $this->authorize('owner', $room);

        $this->validate($request, [
            'capacity' => 'required|numeric|min:1|max:5',
            'description' => 'max:255',
            'notes' => 'max:255',
        ]);

        $this->rooms->update($room, $request->all());

        return redirect()->intended(route('roominglist.index'));
    }

    public function overview()
    {
        $rooms = Room::forUser()->with('tickets.person', 'organization.church', 'hotel')->orderBy('organization_id')->orderBy('id')->get();
        \Session::put('url.intended', route('roominglist.overview'));

        return view('roominglist.overview', compact('rooms'));
    }

    public function issues()
    {
        $organizations = Organization::has('hotelItems')->with('hotelItems', 'rooms')->get();
        $rooms = Room::all();

        $organizations->map(function ($organization) {
            $hotels = $organization->hotelItems->active()
                ->map(function ($hotel) use ($organization) {
                    return [
                        'church' => $organization->church->name,
                        'org id' => $organization->id,
                        'hotel' => $hotel->first()->item->name,
                        'qty' => $hotel->sum('quantity'),
                        'rooms' => $organization->rooms->where('hotel_id', $hotel->first()->item_id)->count(),
                    ];
                })->filter(function ($hotel) {
                    return $hotel['qty'] != $hotel['rooms'];
                });


            // $rooms = $organization->rooms->filter(function ($room) {
            //     return $room->hotel_id;
            // })->each(function ($room) use ($hotels) {
            //     $hotel = $hotels->first(function ($key) use ($room) {
            //         return $key == $room->hotel_id;
            //     }, ['hotel_id' => null, 'qty' => 0]);

            //     $hotels->forget($hotel['hotel_id']);
            //     $hotel['qty']--;
            //     $hotels->offsetSet($hotel['hotel_id'], $hotel);             
            // });
            return [
                $hotels->flatten(),
                // $rooms
            ];
        })->dd();
    }

    public function destroy(Room $room)
    {
        $room->tickets->each(function ($ticket) {
            $ticket->room_id = null;
            $ticket->save();
        });
        $room->delete();
        return redirect()->intended(route('roominglist.overview'));
    }
}
