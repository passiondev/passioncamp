<?php

namespace App\Exports;

use App\Room;
use App\Ticket;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RoomingListVersionExport implements WithMultipleSheets
{
    use Exportable;

    protected $roomingListVersion;

    public function __construct($roomingListVersion)
    {
        $this->roomingListVersion = $roomingListVersion;
    }

    public function sheets(): array
    {
        $rooms = Room::with('organization.church', 'tickets.person')->has('organization')->get();
        
        
        $roomChanges = $rooms->mapWithKeys(function ($room) {
            return [$room->id => $room->revision()];
        });
        
        
        $tickets = Ticket::where('owner_type',"App\Order")->with('order.organization.church')->get();
        
        
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
                'changed' => $roomChanges[$room->id]->propertiesHaveChanged(),
                'gender' => $room->tickets->alphaSort()->pluck('person.gender')->implode(''),
                'tickets' => $room->tickets->alphaSort()->map(function ($ticket) use ($ticketChanges) {
                    return [
                        'first_name' => $ticket->first_name,
                        'last_name' => $ticket->last_name,
                        'changed' => $ticketChanges[$ticket->id]->propertiesHaveChanged() ?? null,
                    ];
                })->toArray(),
            ];
        });

        $changedRooms = $rooms->map(function ($room) use ($roomChanges) {
            return [
                'id' => $room->id,
                'church' => $room->organization->church->name,
                'current' => $roomChanges[$room->id]->properties['attributes'],
                'previous' => collect(['hotelName', 'name', 'description', 'notes'])->mapwithKeys(function ($key) use ($room, $roomChanges) {
                    return [$key => $roomChanges[$room->id]->properties['old'][$key] ?? null];
                })->toArray(),
                'hasChanges' => $roomChanges[$room->id]->propertiesHaveChanged(),
            ];
        })->filter->hasChanges;

        $changedTickets = $tickets->map(function ($ticket) use ($ticketChanges) {
            return [
                'id' => $ticket->id,
                'church' => $ticket->order->organization->church->name ?? '',
                'current' => $ticketChanges[$ticket->id]->properties['attributes'],
                'previous' => collect(['roomId', 'name'])->mapwithKeys(function ($key) use ($ticket, $ticketChanges) {
                    return [$key => $ticketChanges[$ticket->id]->properties['old'][$key] ?? null];
                })->toArray(),
                'hasChanges' => $ticketChanges[$ticket->id]->propertiesHaveChanged(),
            ];
        })->filter->hasChanges;

        $this->roomingListVersion->update([
            'revised_rooms' => $changedRooms->count(),
            'revised_tickets' => $changedTickets->count(),
        ]);

        return [
            new Sheets\AllRoomsSheet($allRooms),
            new Sheets\ChangedRoomsSheet($changedRooms),
            new Sheets\ChangedTicketsSheet($changedTickets),
        ];
    }
}
