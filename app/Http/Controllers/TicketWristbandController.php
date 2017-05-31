<?php

namespace App\Http\Controllers;

use App\Ticket;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class TicketWristbandController extends Controller
{
    public function show(Ticket $ticket)
    {
        return $this->generatePdf($ticket)->stream('dompdf.pdf', ['Attachment' => 0]);
    }

    public function signedShow($payload)
    {
        $ticket = Ticket::findOrFail($payload['id']);

        return $this->show($ticket);
    }

    private function generatePdf($ticket)
    {
        return tap(new Dompdf, function ($dompdf) use ($ticket) {
            $dompdf->loadHtml(view('ticket/wristband', compact('ticket'))->render());
            $dompdf->render();
        });
    }
}
