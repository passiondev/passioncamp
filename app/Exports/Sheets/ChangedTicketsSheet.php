<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class ChangedTicketsSheet implements FromView, WithTitle
{
	protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function view(): View
    {
        return view('roominglist.export.changed_tickets', [
            'tickets' => $this->tickets,
        ]);
    }

    public function title(): string
    {
        return 'Changed Tickets';
    }
}
