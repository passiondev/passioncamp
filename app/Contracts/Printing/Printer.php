<?php

namespace App\Contracts\Printing;

interface Printer
{
    public function print($printer, $url, array $payload);

    public function jobs($printer, $max, $offset);

    public function printers();
}
