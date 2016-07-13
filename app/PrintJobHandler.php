<?php

namespace App;

use App\Printer\PdfPrinter;
use App\Printer\PrintNodePrinter;

class PrintJobHandler
{
    private $printer;
    private $title;

    // public function __construct($printer, $title = 'file', $filename = null)
    // {
    //     $this->printer = $printer;
    //     $this->title = $title;
    // }

    public function withPrinter($printer_id)
    {
        switch ($printer_id) {
            case 'PDF':
                $this->printer = new PdfPrinter;
                break;
            
            default:
                $this->printer = new PrintNodePrinter($printer_id);
                break;
        }

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function output($pdf)
    {
        return $this->printer
            ->title($this->title)
            ->output($pdf);
    }

}