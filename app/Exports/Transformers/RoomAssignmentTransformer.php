<?php

namespace App\Exports\Transformers;

use League\Fractal\TransformerAbstract;

class RoomAssignmentTransformer extends TransformerAbstract
{
    public function transform($roomAssignment)
    {
        return [
            'name' => data_get($roomAssignment, 'room.name'),
            'description' => data_get($roomAssignment, 'room.description'),
            'notes' => data_get($roomAssignment, 'room.notes'),
            'adp_required' => data_get($roomAssignment, 'room.adp_required'),
            'king_preferred' => data_get($roomAssignment, 'room.king_preferred'),
            'hotel' => data_get($roomAssignment, 'room.hotel.name'),
            'room number' => data_get($roomAssignment, 'room.roomnumber'),
        ];
    }
}
