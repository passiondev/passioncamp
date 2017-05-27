<?php

namespace App\Http\Controllers;

use PrintNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PrinterSelectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function store()
    {
        $this->validate(request(), [
            'printer' => 'required'
        ]);

        $printer = Cache::get('printers')[request('printer')];

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
}
