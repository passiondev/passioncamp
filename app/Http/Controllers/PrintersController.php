<?php

namespace App\Http\Controllers;

use PrintNode;
use Illuminate\Http\Request;
use Facades\App\Contracts\Printing\Factory as Printer;

class PrintersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        request()->intended(url()->previous());

        return view('printers.index', [
            'printers' => $this->printDriver()->printers(),
            'jobs' => session('printer.id') ? $this->printDriver()->jobs(session('printer.id')) : []
        ]);
    }

    private function printDriver()
    {
        return Printer::driver(
            data_get(auth()->user(), 'organization.slug') == 'pcc'
            ? 'pcc'
            : null
        );
    }

    public function destroy()
    {
        $this->printDriver()->refreshPrinters();

        return redirect()->back();
    }
}
