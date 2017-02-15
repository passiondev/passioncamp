<?php

namespace App\Printer;

class PdfPrinter extends BasePrinter implements PrinterContract
{
    public function output($pdf)
    {
        return $pdf->Output($this->getFilename());
    }
}
