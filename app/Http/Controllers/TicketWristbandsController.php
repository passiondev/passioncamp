<?php

namespace App\Http\Controllers;

use App\Ticket;
use Dompdf\Dompdf;
use App\Http\Middleware\VerifyPayloadSignature;

class TicketWristbandsController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyPayloadSignature::class);
    }

    public function signedShow($payload)
    {
        $ticket = Ticket::findOrFail($payload['id']);

        return $this->generatePdf($ticket)->stream('wristband.pdf', ['Attachment' => 0]);
    }

    private function generatePdf($ticket)
    {
        return tap(new Dompdf, function ($dompdf) use ($ticket) {
            $dompdf->loadHtml(view('ticket/wristband', compact('ticket'))->render());
            $dompdf->render();
        });
    }
}
