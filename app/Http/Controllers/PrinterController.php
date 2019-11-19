<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;
use Facades\App\Contracts\Printing\Factory as Printer;

class PrinterController extends Controller
{
    public function __construct()
    {
        $this->middleware([Authenticate::class, 'can:print']);
    }

    public function index()
    {
        request()->intended(url()->previous());

        $printers = collect($this->printDriver()->printers())->map(function ($printer) {
            return [
                'id' => $printer->id,
                'name' => $printer->name,
                'computer' => $printer->computer->name,
            ];
        })->sortBy('name')->sortBy('computer');

        return view('printers.index', [
            'printers' => $printers,
            'jobs' => session('printer.id') ? $this->printDriver()->jobs(session('printer.id')) : [],
        ]);
    }

    public function test($printer)
    {
        $this->printDriver()->testPrint($printer);

        return redirect()->back();
    }

    public function refresh()
    {
        $this->printDriver()->refreshPrinters();

        return redirect()->back();
    }

    private function printDriver()
    {
        return Printer::driver(auth()->user()->organization->slug);
    }
}
