<?php

namespace App\Http\Controllers;

use App\Room;
use PrintNode;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyPayloadSignature;
use App\Http\Middleware\VerifyUserHasSelectedPrinter;
use Facades\App\Contracts\Printing\Factory as Printer;

class RoomLabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('signedShow');
        $this->middleware('can:print');
        $this->middleware(VerifyPayloadSignature::class)->only('signedShow');
        $this->middleware(VerifyUserHasSelectedPrinter::class)->only('printnode');
    }

    public function printnode(Room $room)
    {
        Printer::print(
            session('printer'),
            action('RoomLabelController@signedShow', $room->toRouteSignatureArray()),
            [
                'title' => $room->name,
                'source' => $room->organization->church->name
            ]
        );
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
