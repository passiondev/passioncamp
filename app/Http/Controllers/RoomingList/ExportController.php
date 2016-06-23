<?php

namespace App\Http\Controllers\RoomingList;

use App\Room;
use App\Hotel;
use App\Church;
use App\Ticket;
use App\Http\Requests;
use App\RoomingListVersion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\GenerateRoomingListVersion;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('super');
    }

    public function index()
    {
        $versions = RoomingListVersion::all();

        return view('roominglist.export.index', compact('versions', 'churchOptions', 'hotelOptions'));
    }

    public function version(Request $request)
    {
        $job = (new GenerateRoomingListVersion)->onQueue('roominglist');
        dispatch($job);

        return redirect()->back()->withLoading('A new export is being generated. Stay on this page and once the export is complete, it will download automatically. Or, come back to this page in a few minutes and download it from the list below.');
    }

    public function download(RoomingListVersion $version)
    {
        return response()->download($version->file_path);
    }
}
