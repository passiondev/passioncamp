<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\UrlGenerator;

class RoominglistPrinterController extends Controller
{
    public function index()
    {
        return view('roominglist.printer.index')->withPrefix('checkin');
    }

    public function select(Request $request, UrlGenerator $generator, $printer)
    {
        $request->session()->put('printer', $printer);

        return redirect()->intended($generator->previous())->withSuccess('Printer selected.');
    }

    public function reset(Request $request)
    {
        $request->session()->forget('printer');

        return redirect()->back();
    }
}
