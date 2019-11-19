<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ChangedRoomsSheet implements FromView, WithTitle
{
    protected $rooms;

    public function __construct($rooms)
    {
        $this->rooms = $rooms;
    }

    public function view(): View
    {
        return view('roominglist.export.changed_rooms', [
            'rooms' => $this->rooms,
        ]);
    }

    public function title(): string
    {
        return 'Changed Rooms';
    }
}
