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
        $rooms = Room::with('organization.church')->get();
        $tickets = Ticket::with('order.organization.church')->get();

        \DB::beginTransaction();

        $roomChanges = $rooms->mapWithKeys(function ($room) {
            return [$room->id => $room->revision()];
        });

        $ticketChanges = $tickets->mapWithKeys(function ($ticket) {
            return [$ticket->id => $ticket->revision()];
        });

        $allRooms = $rooms->map(function ($room) use ($roomChanges, $ticketChanges) {
            return [
                'id' => $room->id,
                'confirmation_number' => $room->confirmation_number,
                'church' => $room->organization->church->name,
                'hotel' => $room->hotelName,
                'name' => $room->name,
                'desc' => $room->description,
                'notes' => $room->notes,
                'changed' => $roomChanges[$room->id]->hasChanges(),
                'gender' => $room->tickets->pluck('person.gender')->implode(''),
                'tickets' => $room->tickets->map(function ($ticket) use ($ticketChanges) {
                    return [
                        'first_name' => $ticket->first_name,
                        'last_name' => $ticket->last_name,
                        'changed' => isset($ticketChanges[$ticket->id]) ? $ticketChanges[$ticket->id]->hasChanges() : null,
                    ];
                })->toArray(),
            ];
        });

        $rooms = $rooms->map(function ($room) use ($roomChanges) {
            return [
                'id' => $room->id,
                'church' => $room->organization->church->name,
                'current' => $roomChanges[$room->id]->properties['attributes'],
                'previous' => collect(['hotelName', 'name', 'description', 'notes'])->mapwithKeys(function ($key) use ($room, $roomChanges) {
                    return [$key => $roomChanges[$room->id]->properties['old'][$key] ?? null];
                })->toArray(),
                'hasChanges' => $roomChanges[$room->id]->hasChanges(),
            ];
        })->filter->hasChanges;

        $tickets = $tickets->map(function ($ticket) use ($ticketChanges) {
            return [
                'id' => $ticket->id,
                'church' => $ticket->order->organization->church->name ?? '',
                'current' => $ticketChanges[$ticket->id]->properties['attributes'],
                'previous' => collect(['roomId', 'name'])->mapwithKeys(function ($key) use ($ticket, $ticketChanges) {
                    return [$key => $ticketChanges[$ticket->id]->properties['old'][$key] ?? null];
                })->toArray(),
                'hasChanges' => $ticketChanges[$ticket->id]->hasChanges(),
            ];
        })->filter->hasChanges;

        $version = RoomingListVersion::create([
            'revised_tickets' => $tickets->count(),
            'revised_rooms' => $rooms->count(),
            'user_id' => 1,
        ]);

        \DB::commit();

        $title = 'Rooming List Export - Version #' . $version->id;

        $document = Excel::create($title, function ($excel) use ($allRooms, $rooms, $tickets) {
            $excel->sheet('All Rooms', function ($sheet) use ($allRooms) {
                $sheet->loadView('roominglist.export.all_rooms', compact('allRooms'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:Q1');
            });
            $excel->sheet('Changed Rooms', function ($sheet) use ($rooms) {
                $sheet->loadView('roominglist.export.changed_rooms', compact('rooms'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:J1');
            });
            $excel->sheet('Changed Tickets', function ($sheet) use ($tickets) {
                $sheet->loadView('roominglist.export.changed_tickets', compact('tickets'))
                      ->freezeFirstRow()
                      ->setAutoFilter('A1:H1');
            });
            $excel->setActiveSheetIndex(0);
        })->store('xlsx', false, true);

        $version->file_path = $document['full'];
        $version->save();

        Pusher::trigger('roominglist.export', 'generated', ['version' => $version->id, 'file_path' => $document['full']]);
    }
}
