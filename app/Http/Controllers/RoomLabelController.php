<?php

namespace App\Http\Controllers;

use App\Room;
use PrintNode;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyPayloadSignature;
use App\Http\Middleware\VerifyUserHasSelectedPrinter;

class RoomLabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('signedShow');
        $this->middleware(VerifyPayloadSignature::class)->only('signedShow');
        $this->middleware(VerifyUserHasSelectedPrinter::class)->only('printnode');
    }

    public function printnode(Room $room, PrintNode\Client $client)
    {
        $printJob = new PrintNode\Entity\PrintJob($client);
        $printJob->printer = session('printer');
        $printJob->title = $room->name;
        $printJob->source = $room->organization->church->name;
        $printJob->contentType = 'pdf_uri';
        $printJob->content = action('RoomLabelController@signedShow', $room->toRouteSignatureArray());

        return $client->createPrintJob($printJob);
    }

    public function show(Room $room)
    {
        return $this->generatePdf($room)->stream('dompdf.pdf', ['Attachment' => 0]);
    }

    public function signedShow($payload)
    {
        $room = Room::findOrFail($payload['id']);

        return $this->show($room);
    }

    private function generatePdf($room)
    {
        return tap(new Dompdf, function ($dompdf) use ($room) {
            $dompdf->loadHtml(view('room/label', compact('room'))->render());
            $dompdf->render();
        });
    }
}
