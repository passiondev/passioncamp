<?php

namespace App\Jobs;

use App\Room;
use App\Ticket;
use App\Jobs\Job;
use App\RoomingListVersion;
use Vinkla\Pusher\Facades\Pusher;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateRoomingListVersion extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rooms = Room::with('latestRevision', 'organization.church', 'hotel')->get();
        $tickets = Ticket::with('person', 'organization.church', 'latestRevision')->get();

        \DB::beginTransaction();

        $changed_rooms = $rooms->each(function ($room) {
            $room->revision();
        })->load('latestRevision')->filter(function ($room) {
            return $room->has_changed_since_last_revision;
        });

        $changed_tickets = $tickets->each(function ($ticket) {
            $ticket->revision();
        })->load('latestRevision')->filter(function ($ticket) {
            return $ticket->has_changed_since_last_revision;
        });

        $version = new RoomingListVersion;
        $version->forceFill([
            'revised_tickets' => $changed_tickets->count(),
            'revised_rooms' => $changed_rooms->count(),
        ])->save();

        $all_rooms = $rooms->load('tickets.latestRevision', 'tickets.person')->map(function ($room) {
            return [
                'id'        => $room->id,
                'confirmation_number'        => $room->confirmation_number,
                'church'    => $room->organization->church->name,
                'hotel'     => $room->hotel_name,
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

        $changed_rooms = $changed_rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'church' => $room->organization->church->name,
                'current' => [
                    'hotel' => $room->latestRevision ? $room->latestRevision->new('hotel') : $room->hotel_name,
                    'name' => $room->latestRevision ? $room->latestRevision->new('name') : $room->name,
                    'desc' => $room->latestRevision ? $room->latestRevision->new('description') : $room->description,
                    'notes' => $room->latestRevision ? $room->latestRevision->new('notes') : $room->notes,
                ],
                'previous' => [
                    'hotel' => $room->latestRevision ? $room->latestRevision->old('hotel') : '',
                    'name' => $room->latestRevision ? $room->latestRevision->old('name') : '',
                    'desc' => $room->latestRevision ? $room->latestRevision->old('description') : '',
                    'notes' => $room->latestRevision ? $room->latestRevision->old('notes') : '',
                ],
            ];
        });

        $changed_tickets = $changed_tickets->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'church' => $ticket->organization->church->name,
                'current' => [
                    'room_id' => $ticket->latestRevision ? $ticket->latestRevision->new('room_id') : $ticket->room_id,
                    'fname' => $ticket->latestRevision ? $ticket->latestRevision->new('fname') : $ticket->first_name,
                    'lname' => $ticket->latestRevision ? $ticket->latestRevision->new('lname') : $ticket->last_name,
                ],
                'previous' => [
                    'room_id' => $ticket->latestRevision ? $ticket->latestRevision->old('room_id') : '',
                    'fname' => $ticket->latestRevision ? $ticket->latestRevision->old('fname') : '',
                    'lname' => $ticket->latestRevision ? $ticket->latestRevision->old('lname') : '',
                ],
            ];
        });

        \DB::commit();

        $title = 'Rooming List Export';
        if ($version) {
            $title = $title . ' - Version #' . $version->id;
        }
        $document = Excel::create($title, function ($excel) use ($all_rooms, $changed_rooms, $changed_tickets) {
            $excel->sheet('All Rooms', function ($sheet) use ($all_rooms) {
                $sheet->loadView('roominglist.export.all_rooms', compact('all_rooms'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:Q1');
            });
            $excel->sheet('Changed Rooms', function ($sheet) use ($changed_rooms) {
                $sheet->loadView('roominglist.export.changed_rooms', compact('changed_rooms'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:J1');
            });
            $excel->sheet('Changed Tickets', function ($sheet) use ($changed_tickets) {
                $sheet->loadView('roominglist.export.changed_tickets', compact('changed_tickets'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:H1');
            });
            $excel->setActiveSheetIndex(0);
        // })->download('xlsx');
        })->store('xlsx', false, true);

        $version->file_path = $document['full'];
        $version->save();

        Pusher::trigger('roominglist.export', 'generated', ['version' => $version->id, 'file_path' => $document['full']]);
    }
}
