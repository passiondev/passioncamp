<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use PrintNode\Request as PrintNode;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    private $printnode;

    public function __construct(PrintNode $printnode)
    {
        $this->printnode = $printnode;
    }

    public function index()
    {
        $printers = collect($this->printnode->getPrinters());

        return view('printer.index', compact('printers'));
    }

    public function select(Request $request, $printer)
    {
        $request->session()->put('printer', $printer);

        return redirect()->back();
    }
}
