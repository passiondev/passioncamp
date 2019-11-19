<?php

namespace App\Http\Controllers;

use App\Room;
use PrintNode;
use Dompdf\Dompdf;
use App\Filters\RoomFilters;
use App\Jobs\PrintRoomLabelJob;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use App\Http\Middleware\VerifyUserHasSelectedPrinter;
use Facades\App\Contracts\Printing\Factory as Printer;

class RoomLabelController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class)->except('show');

        $this->middleware(function ($request, $next) {
            if (! Auth::check() && ! $request->hasValidSignature()) {
                throw new AuthenticationException;
            }

            return $next($request);
        })->only('show');

        $this->middleware(VerifyUserHasSelectedPrinter::class)->only('printnode');
    }

    public function printAll(RoomFilters $filters)
    {
        $rooms = Room::filter($filters)->get();

        $rooms->each(function ($room) {
            PrintRoomLabelJob::dispatch($room, session('printer'));
        });

        return redirect()->back();
    }

    public function printnode(Room $room)
    {
        Printer::print(
            session('printer'),
            url()->signedRoute('room.label.show', $room),
            [
                'title' => $room->name,
                'source' => $room->organization->church->name,
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
