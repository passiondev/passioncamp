<?php

namespace App\Http\Controllers;

use App\Room;
use App\Hotel;
use Dompdf\Dompdf;
use App\Organization;
use App\Filters\RoomFilters;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(RoomFilters $filters)
    {
        $rooms = Room::filter($filters)->with(['tickets.person', 'organization' => function ($q) {
            $q->withoutGlobalScopes();
        }, 'organization.church'])->orderByChurchName()->orderBy('id')->get();

        $organizations = Organization::select('organizations.*')->with('church')->join('churches', 'church_id', '=', 'churches.id')->orderBy('churches.name')->withoutGlobalScopes()->get();
        $hotels = Hotel::select('*')->orderBy('name')->withoutGlobalScopes(['registeredSum'])->get();

        return request()->expectsJson()
            ? $rooms
            : view('room.index', compact('rooms', 'organizations', 'hotels'));
    }

    public function edit(Room $room)
    {
        request()->intended(url()->previous());

        $this->authorize($room);

        return view('room.edit', compact('room'));
    }

    public function update(Room $room)
    {
        $this->authorize($room);

        $room->update(
            auth()->user()->isSuperAdmin()
            ? request([
                'capacity', 'description', 'notes',
                'name', 'roomnumber', 'confirmation_number',
                'is_checked_in', 'is_key_received',
            ])
            : request(['capacity', 'description', 'notes'])
        );

        return redirect()->intended(action('RoomingListController@index'));
    }

    public function checkin(Room $room)
    {
        $room->checkin();

        return request()->expectsJson()
            ? response()->json($room, 204)
            : redirect()->back();
    }

    public function keyReceived(Room $room)
    {
        $room->keyReceived();

        return request()->expectsJson()
            ? response()->json($room, 204)
            : redirect()->back();
    }

    public function label(Room $room)
    {
        return view('room/label', compact('room'));
        $pdf = new Dompdf;
        $pdf->loadHtml(view('room/label', compact('room'))->render());
        $pdf->setPaper('c7', 'portrait');
        $pdf->render();
        $pdf->stream("dompdf_out.pdf", array("Attachment" => false));
        exit;

        // $pdf = new \HTML2PDF('P', [50.8,58.7], 'en', true, 'UTF-8', 0);
        // $pdf->writeHTML(view('room/label', compact('room'))->render());
        // $pdf->output();

        // if (request()->expectsJson()) {
        //     $pdf = new \HTML2PDF('P', [50.8,58.7], 'en', true, 'UTF-8', 0);
        //     $pdf->writeHTML(view('roominglist/partials/label', compact('room'))->render());

        //     $handler = new PrintJobHandler(RoominglistPrintNodeClient::init());
        //     $handler->withPrinter(session('printer'))
        //         ->setTitle($room->name)
        //         ->output($pdf);

        //     return response('Created', 201);
        // } else {
        //     return redirect()->back()->withSuccess('Printing job queued.');
        // }
    }
}
