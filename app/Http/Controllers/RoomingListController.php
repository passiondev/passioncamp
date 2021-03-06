<?php

namespace App\Http\Controllers;

use App\Room;
use App\Hotel;
use App\Ticket;
use App\Organization;
use App\PrintJobHandler;
use Illuminate\Http\Request;
use App\Repositories\RoomRepository;
use App\Repositories\TicketRepository;
use App\Http\Requests\UpdateRoomRequest;
use App\PrintNode\RoominglistPrintNodeClient;

class RoomingListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tickets = Ticket::forUser()
            ->active()
            ->with('person', 'order')
            ->scopes(['withRoom'])
            ->orderBy('agegroup')
            ->get()
            ->map(function ($ticket) use (&$i) {
                return [
                    'id' => $ticket->id,
                    'name' => $ticket->name,
                    'gender' => $ticket->person->gender,
                    'type' => $ticket->agegroup,
                    'grade' => $ticket->person->grade ? number_ordinal($ticket->person->grade) : null,
                    'room_id' => optional($ticket->room)->id,
                    'organization_id' => $ticket->order->organization_id,
                    'assigned_sort' => $ticket->assigned_sort,
                    'unassigned_sort' => $ticket->unassigned_sort,
                ];
            });

        $rooms = Room::forUser()
            ->with('tickets.person')
            ->when(auth()->user()->isSuperAdmin(), function ($q) {
                $q->with('organization.church');
            })
            ->get();

        return view('roominglist.index', compact('tickets', 'rooms'));
    }

    public function show(Room $room)
    {
        $this->authorize('owner', $room);

        return request()->expectsJson()
            ? response()->json([
                'view' => view('roominglist.partials.room', ['room' => $room->fresh('tickets')])->render()
            ])
            : redirect()->route('roominglist.index');
    }

    public function assign(Request $request, Ticket $ticket, Room $room)
    {
        $this->authorize('owner', $ticket);
        $this->authorize('owner', $room);

        try {
            $this->rooms->assign($room, $ticket);
        } catch (\Exception $e) {
            return request()->expectsJson()
                ? response()->json([
                    'message' => $e->getMessage(),
                    'view' => view('roominglist.partials.room', ['room' => $room->fresh('tickets')])->render()
                ], 400)
                : abort(400, $e->getMessage());
        }

        return request()->expectsJson()
            ? response()->json([
                'view' => view('roominglist.partials.room', ['room' => $room->fresh('tickets')])->render()
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

        $hotelOptions = Hotel::all()->sortBy('name')->keyBy('id')->pluck('name');

        return view('roominglist.edit', compact('room', 'hotelOptions'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $this->authorize('owner', $room);

        $room->fill($request->all())->save();

        return redirect()->intended(route('roominglist.index'));
    }

    public function overview()
    {
        session('url.intended', route('roominglist.overview'));

        $rooms = Room::forUser()
            ->with('tickets.person', 'organization.church', 'hotel')
            ->orderBy('organization_id')
            ->orderBy('id')
            ->get();

        $organizations = Organization::has('rooms')
            ->with('church')
            ->orderByChurchName()
            ->get();

        return view('roominglist.overview', compact('rooms', 'organizations'));
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

    public function label(Request $request, Room $room)
    {
        $pdf = new \HTML2PDF('P', [50.8,58.7], 'en', true, 'UTF-8', 0);
        $pdf->writeHTML(view('roominglist/partials/label', compact('room'))->render());

        $handler = new PrintJobHandler(RoominglistPrintNodeClient::init());
        $handler->withPrinter($request->session()->get('printer'))->setTitle($room->name)->output($pdf);

        if ($request->ajax() || $request->wantsJson()) {
            return response('<i class="checkmark green icon"></i>', 201);
        } else {
            return redirect()->back()->withSuccess('Printing job queued.');
        }
    }

    public function checkin(Request $request, Room $room)
    {
        $room->checkin();

        return request()->expectsJson()
            ? response('<i class="checkmark green icon"></i> checked in', 200)
            : redirect()->back()->withSuccess('Room checked in.');
    }

    public function keyReceived(Request $request, Room $room)
    {
        $room->keyReceived();

        return request()->expectsJson()
            ? response('<i class="checkmark green icon"></i> key', 200)
            : redirect()->back()->withSuccess('Room key received.');
    }
}
