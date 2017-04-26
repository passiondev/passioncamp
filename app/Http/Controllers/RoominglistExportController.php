<?php

namespace App\Http\Controllers;

use App\RoomingListVersion;
use Illuminate\Http\Request;
use App\Jobs\GenerateRoomingListVersion;

class RoominglistExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function create()
    {
        dispatch(new GenerateRoomingListVersion);

        return redirect()->back()->withLoading('A new export is being generated. Stay on this page and once the export is complete, it will download automatically. Or, come back to this page in a few minutes and download it from the list below.');
    }


    public function download(RoomingListVersion $version)
    {
        return response()->download($version->file_path);
    }
}
