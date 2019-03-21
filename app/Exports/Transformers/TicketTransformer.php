<?php

namespace App\Exports\Transformers;

use League\Fractal\TransformerAbstract;

class TicketTransformer extends TransformerAbstract
{
    private $includeAdditionalFields;

    protected $defaultIncludes = ['contact', 'roomAssignment'];

    public function __construct($includeAdditionalFields = false)
    {
        $this->includeAdditionalFields = $includeAdditionalFields;
    }

    public function transform($ticket)
    {
        $data = [
            'id' => $ticket->id,
            'created at' => $ticket->created_at->toDateTimeString(),
            'updated at' => $ticket->updated_at->toDateTimeString(),
            'type' => $ticket->agegroup,
            'first name' => $ticket->person->first_name,
            'last name' => $ticket->person->last_name,
            'gender' => $ticket->person->gender,
            'grade' => $ticket->person->grade,
            'camp waiver' => data_get($ticket, 'waiver.is_complete') ? 'X' : '',
        ];

        $data += $ticket->person->formatted_considerations;

        if ($this->includeAdditionalFields) {
            $data += [
                'church' => $ticket->order->organization->church->name,
                'email' => $ticket->email,
                'phone' => $ticket->phone,
                'birthdate' => $ticket->birthdate ? $ticket->birthdate->toDateString() : null,
                'shirtsize' => $ticket->shirtsize,
                'school' => $ticket->school,
                'roommate requested' => $ticket->roommate_requested,
                'squad' => $ticket->squad,
                'leader' => $ticket->leader,
                'bus' => $ticket->bus,
                'travel plans' => $ticket->travel_plans,
                'pcc waiver?' => $ticket->pcc_waiver,
                'price' => $ticket->price / 100,
                'checked_in_at' => $ticket->checked_in_at,
                'code' => $ticket->code,
                'rep' => $ticket->order->order_data->get('rep'),
            ];
        }

        return $data;
    }

    public function includeContact($ticket)
    {
        return $this->item($ticket->order->user->person, new ContactTransformer);
    }

    public function includeRoomAssignment($ticket)
    {
        return $this->item($ticket->roomAssignment, new RoomAssignmentTransformer);
    }

    // public function includeGroup($ticket)
    // {
    //     return $this->item($ticket->groupTicket->group, new GroupTransformer);
    // }

    // public function includeContact($ticket)
    // {
    //     return $this->item($ticket->groupTicket->contact, new ContactTransformer);
    // }

    // public function includeInviter($ticket)
    // {
    //     return $this->item($ticket->groupTicket->inviter, new InviterTransformer);
    // }
}
