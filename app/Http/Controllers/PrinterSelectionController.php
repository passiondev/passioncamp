<?php

namespace App\Http\Controllers;

use PrintNode;
use Illuminate\Http\Request;
use Facades\App\Contracts\Printing\Factory as Printer;

class PrinterSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        $this->validate(request(), [
            'printer' => 'required'
        ]);

        $printer = $this->printDriver()->printers()[request('printer')];

        session(['printer' => [
            'id' => $printer->id,
            'name' => $printer->computer->name,
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
        return Printer::driver(
            data_get(auth()->user(), 'organization.slug') == 'pcc'
            ? 'pcc'
            : null
        );
    }
}
