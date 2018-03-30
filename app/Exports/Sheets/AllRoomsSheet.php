<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class AllRoomsSheet implements FromView, WithTitle
{
	protected $rooms;

    public function __construct($rooms)
    {
        $this->rooms = $rooms;
    }

    public function view(): View
    {
        return view('roominglist.export.all_rooms', [
            'allRooms' => $this->rooms,
        ]);
    }

    public function title(): string
    {
        return 'All Rooms';
    }
}
