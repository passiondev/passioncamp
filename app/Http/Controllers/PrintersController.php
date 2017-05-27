<?php

namespace App\Http\Controllers;

use PrintNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class PrintersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index(PrintNode\Client $client)
    {
        request()->intended(url()->previous());

        $printers = Cache::remember('printers', 10, function () use ($client) {
            return $client->viewPrinters();
        });

        $jobs = Session::has('printer') ? $client->viewPrintJobs(0, 500, null, Session::get('printer.id')) : [];

        return view('printers.index', compact('printers', 'jobs'));
    }

    public function destroy()
    {
        Cache::forget('printers');

        return redirect()->back();
    }
}
