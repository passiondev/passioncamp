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
        $churchOptions = Church::has('rooms')->get()->sortBy('name')->keyBy('id')->map(function ($church) { return $church->name . ' - ' . $church->location; });
        $hotelOptions = Hotel::all()->sortBy('name')->keyBy('id')->map(function ($hotel) { return $hotel->name; });

        return view('roominglist.export.index', compact('versions', 'churchOptions', 'hotelOptions'));
    }

    public function version(Request $request)
    {
        $job = (new GenerateRoomingListVersion)->onQueue('roominglist');
        dispatch($job);

        return redirect()->back()->withSuccess('Once the export has been generated, it will download automatically. Or come back to this page in a few minutes and download it from the list below.');
    }

    public function download(RoomingListVersion $version)
    {
        return response()->download($version->file_path);
    }
}
