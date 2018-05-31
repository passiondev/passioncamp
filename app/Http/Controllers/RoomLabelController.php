<?php

namespace App\Http\Controllers;

use App\Room;
use PrintNode;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyPayloadSignature;
use App\Http\Middleware\VerifyUserHasSelectedPrinter;
use Facades\App\Contracts\Printing\Factory as Printer;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class RoomLabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');

        $this->middleware(function ($request, $next) {
            if (! Auth::check() && ! $request->hasValidSignature()) {
                throw new AuthenticationException;
            }

            return $next($request);
        })->only('show');

        $this->middleware(VerifyUserHasSelectedPrinter::class)->only('printnode');
    }

    public function printnode(Room $room)
    {
        Printer::print(
            session('printer'),
            url()->signedRoute('room.label.show', $room),
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

    private function generatePdf($room)
    {
        return tap(new Dompdf, function ($dompdf) use ($room) {
            $dompdf->loadHtml(view('room/label', compact('room'))->render());
            $dompdf->render();
        });
    }
}
