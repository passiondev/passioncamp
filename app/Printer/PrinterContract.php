<?php

namespace App\Printer;

interface PrinterContract
{
    public function output($pdf);
}