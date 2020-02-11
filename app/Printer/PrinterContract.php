<?php

namespace App\Printer;

use Spipu\Html2Pdf\Html2Pdf;

interface PrinterContract
{
    public function output(Html2Pdf $pdf);
}
