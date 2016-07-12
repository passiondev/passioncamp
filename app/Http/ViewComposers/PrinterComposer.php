<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use PrintNode\Request as PrintNode;
use Illuminate\Support\Facades\Session;

class PrinterComposer
{
    private $printnode;

    public function __construct(PrintNode $printnode)
    {
        $this->printnode = $printnode;
    }

    public function compose(View $view)
    {
        $jobs = Session::has('printer') && Session::get('printer') != 'PDF' ? $this->printnode->getPrintJobsByPrinters(Session::get('printer')) : [];

        $view->with('jobs', collect($jobs)->reverse()->take(5));
        $view->with('printers', $this->printnode->getPrinters());
    }
}
