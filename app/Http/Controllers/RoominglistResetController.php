<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoominglistResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function __invoke()
    {
        DB::statement("TRUNCATE TABLE activity_log;");
        DB::statement("TRUNCATE TABLE rooming_list_versions;");
        DB::statement("ALTER TABLE rooming_list_versions AUTO_INCREMENT = 1");

        return redirect()->back()
            ->with('success', 'Reset');
    }
}
