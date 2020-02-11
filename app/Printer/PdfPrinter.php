<?php

namespace App\Printer;

use Spipu\Html2Pdf\Html2Pdf;

class PdfPrinter extends BasePrinter implements PrinterContract
{
    public function output(Html2Pdf $pdf)
    {
        return $pdf->output($this->getFilename());
    }
}
