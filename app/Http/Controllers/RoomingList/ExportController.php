<?php

namespace App\Http\Controllers\RoomingList;

use App\Room;
use App\Ticket;
use App\Http\Requests;
use App\RoomingListVersion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('super');
    }

    public function index()
    {
        $versions = RoomingListVersion::all();

        return view('roominglist.export.index', compact('versions'));
    }

    public function version(Request $request)
    {
        $rooms = Room::with('latestRevision', 'tickets.person', 'tickets.latestRevision', 'organization.church')->get();
        $tickets = Ticket::with('person', 'organization.church', 'latestRevision')->get();

        $changed_rooms = $rooms->sometimes($request->save_changeset, 'each', function ($room) {
            $room->revision();
        })->load('latestRevision')->filter(function ($room) {
            return $room->has_changed_since_last_revision;
        });

        $changed_tickets = $tickets->sometimes($request->save_changeset, 'each', function ($ticket) {
            $ticket->revision();
        })->load('latestRevision')->filter(function ($ticket) {
            return $ticket->has_changed_since_last_revision;
        });

        $version = new RoomingListVersion;
        $version->forceFill([
            'user_id' => Auth::user()->id,
            'revised_tickets' => $changed_tickets->count(),
            'revised_rooms' => $changed_rooms->count(),
        ])->save();

        return redirect()->route('roominglist.export');
    }

    public function generate(Request $request)
    {
        $rooms = Room::with('latestRevision', 'tickets.person', 'tickets.latestRevision', 'organization.church')->get();
        $tickets = Ticket::with('person', 'organization.church', 'latestRevision')->get();
        $versions = RoomingListVersion::all();

        $all_rooms = $rooms->map(function ($room) {
            return [
                'id'        => $room->id,
                'church'    => $room->organization->church->name,
                'name'      => $room->name,
                'desc'      => $room->description,
                'notes'     => $room->notes,
                'changed'   => $room->has_changed_since_last_revision,
                'gender'    => $room->tickets->pluck('person.gender')->implode(''),
                'tickets'   => $room->tickets->map(function ($ticket) {
                                return [
                                    'fname' => $ticket->person->first_name,
                                    'lname' => $ticket->person->last_name,
                                    'changed' => $ticket->has_changed_since_last_revision,
                                ];
                            })->toArray(),
            ];
        });

        $changed_rooms = $rooms->filter(function ($room) {
            return $room->has_changed_since_last_revision;
        })->map(function ($room) {
            return [
                'id' => $room->id,
                'church' => $room->organization->church->name,
                'current' => [
                    'name' => $room->latestRevision ? $room->latestRevision->new('name') : $room->name,
                    'desc' => $room->latestRevision ? $room->latestRevision->new('description') : $room->description,
                    'notes' => $room->latestRevision ? $room->latestRevision->new('notes') : $room->notes,
                ],
                'previous' => [
                    'name' => $room->latestRevision->old('name'),
                    'desc' => $room->latestRevision->old('description'),
                    'notes' => $room->latestRevision->old('notes'),
                ],
            ];
        });

        $changed_tickets = $tickets->filter(function ($ticket) {
            return $ticket->has_changed_since_last_revision;
        })->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'church' => $ticket->organization->church->name,
                'current' => [
                    'room_id' => $ticket->latestRevision ? $ticket->latestRevision->new('room_id') : $ticket->room_id,
                    'fname' => $ticket->latestRevision ? $ticket->latestRevision->new('fname') : $ticket->first_name,
                    'lname' => $ticket->latestRevision ? $ticket->latestRevision->new('lname') : $ticket->last_name,
                ],
                'previous' => [
                    'room_id' => $ticket->latestRevision->old('room_id'),
                    'fname' => $ticket->latestRevision->old('fname'),
                    'lname' => $ticket->latestRevision->old('lname'),
                ],
            ];
        });

        Excel::create('Rooming List Export - Version #'. $versions->last()->id, function($excel) use ($all_rooms, $changed_rooms, $changed_tickets) {
            $excel->sheet('All Rooms', function($sheet) use ($all_rooms) {
                $sheet->loadView('roominglist.export.all_rooms', compact('all_rooms'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:P1');
            });
            $excel->sheet('Changed Rooms', function($sheet) use ($changed_rooms) {
                $sheet->loadView('roominglist.export.changed_rooms', compact('changed_rooms'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:H1');
            });
            $excel->sheet('Changed Tickets', function($sheet) use ($changed_tickets) {
                $sheet->loadView('roominglist.export.changed_tickets', compact('changed_tickets'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:H1');
            });
            $excel->setActiveSheetIndex(0);
        })->download('xlsx');
    }
}
