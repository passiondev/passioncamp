<?php

namespace App\Printer;

use App\Jobs\PrintNodeJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PrintNodePrinter extends BasePrinter implements PrinterContract
{
    use DispatchesJobs;

    private $printer_id;

    public function __construct($printer_id)
    {
        $this->printer_id = $printer_id;
    }

    public function getContent($pdf)
    {
        return base64_encode($pdf->Output($this->getFilename(), 'S'));
    }

    public function output($pdf)
    {
        $this->dispatch(
            new PrintNodeJob($this->printer_id, $this->getTitle(), $this->getContent($pdf))
        );
    }
}