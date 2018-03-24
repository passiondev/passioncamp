<?php

namespace App\Http\Controllers;

use PrintNode;
use Illuminate\Http\Request;
use Facades\App\Contracts\Printing\Factory as Printer;

class PrinterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:print');
    }

    public function index()
    {
        request()->intended(url()->previous());

        return view('printers.index', [
            'printers' => $this->printDriver()->printers(),
            'jobs' => session('printer.id') ? $this->printDriver()->jobs(session('printer.id')) : []
        ]);
    }

    public function test($printer)
    {
        $this->printDriver()->testPrint($printer);

        return redirect()->back();
    }

    public function destroy()
    {
        $this->printDriver()->refreshPrinters();

        return redirect()->back();
    }

    private function printDriver()
    {
        return Printer::driver(auth()->user()->organization->slug);
    }
}
