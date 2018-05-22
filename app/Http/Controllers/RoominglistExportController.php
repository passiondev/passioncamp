<?php

namespace App\Http\Controllers;

use App\RoomingListVersion;
use Illuminate\Http\Request;
use App\Jobs\GenerateRoomingListVersion;
use App\Exports\RoomingListVersionExport;
use Illuminate\Support\Facades\Storage;
use App\Jobs\NotifyUserOfCompletedExport;
use App\Jobs\GenerateRoomingListVersionExport;

class RoominglistExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function create()
    {
        $version = RoomingListVersion::create([
            'user_id' => request()->user()->id,
        ]);

        GenerateRoomingListVersionExport::withChain([
            new NotifyUserOfCompletedExport(request()->user(), $version),
        ])->onQueue('long-running-queue')->dispatch($version);

        return redirect()->back()->withLoading('A new export is being generated. Stay on this page and once the export is complete, it will download automatically. Or, come back to this page in a few minutes and download it from the list below.');
    }


    public function download(RoomingListVersion $version)
    {
        return Storage::disk('exports')->download($version->file_name);
    }
}
