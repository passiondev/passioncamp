<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Contracts\Printing\Factory as Printer;

class SelectedPrinterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:print');
    }

    public function store()
    {
        request()->validate([
            'printer' => 'required',
        ]);

        $printer = $this->printDriver()->printers()[request('printer')];

        session(['printer' => [
            'id' => $printer->id,
            'name' => $printer->computer->name . ' :: ' . $printer->name,
        ]]);

        return redirect()->intended(url()->previous());
    }

    public function destroy()
    {
        session()->forget('printer');

        return redirect()->back();
    }

    private function printDriver()
    {
        return Printer::driver(auth()->user()->organization->slug);
    }
}
