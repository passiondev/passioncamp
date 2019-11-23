<?php

namespace App\Http\Controllers\Super;

use App\RoomingListVersion;
use App\Http\Controllers\Controller;

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

        return view('super.roominglists.index', compact('versions'));
    }
}
