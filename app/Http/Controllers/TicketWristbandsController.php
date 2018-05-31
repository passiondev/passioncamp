<?php

namespace App\Http\Controllers;

use App\Ticket;
use Dompdf\Dompdf;
use Illuminate\Routing\Middleware\ValidateSignature;

class TicketWristbandsController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateSignature::class);
    }

    public function show(Ticket $ticket)
    {
        return $this->generatePdf($ticket)
            ->stream('wristband.pdf', ['Attachment' => 0]);
    }

    private function generatePdf($ticket)
    {
        return tap(new Dompdf, function ($dompdf) use ($ticket) {
            $dompdf->loadHtml(view('ticket.wristband', compact('ticket'))->render());
            $dompdf->render();
        });
    }
}
