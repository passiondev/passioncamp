<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\RoomingListVersion;
use Illuminate\Support\Facades\Storage;

class RoominglistsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index()
    {
        $versions = RoomingListVersion::all();
        
        

        $files = Storage::files('exports');
        //dd($files);

        return view('super.roominglists.index', compact('versions', 'files'));
    }
}
