<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use PrintNode\Client as PrintNode;
use Illuminate\Support\Facades\Session;

class CheckInPrinterIndexComposer
{
    private $printnode;

    public function __construct(PrintNode $printnode)
    {
        $this->printnode = $printnode;
    }

    public function compose(View $view)
    {
        $jobs = Session::has('printer') && 'PDF' != Session::get('printer') ? $this->printnode->viewPrintJobs(0, 500, null, Session::get('printer')) : [];

        $jobs = collect($jobs)->map(function ($job) {
            $job->createTimestamp = \Carbon\Carbon::parse($job->createTimestamp);

            return $job;
        })->reverse()->take(5);

        $view->with('jobs', $jobs);
        $view->with('printers', $this->printnode->viewPrinters());
    }
}
