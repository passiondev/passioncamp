<?php

namespace App;

use App\Printer\PdfPrinter;
use App\Printer\PrintNodePrinter;
use PrintNode\Client as PrintNode;
use Spipu\Html2Pdf\Html2Pdf;

class PrintJobHandler
{
    private $printer;

    private $title;

    private $printNode;

    public function __construct(PrintNode $printNode)
    {
        $this->printNode = $printNode;
    }

    public function withPrinter($printer_id)
    {
        switch ($printer_id) {
            case 'PDF':
                $this->printer = new PdfPrinter();

                break;
            default:
                $this->printer = new PrintNodePrinter($this->printNode, $printer_id);

                break;
        }

        return $this;
    }

    public function setTitle($title)
    {
        $this->title = (string) $title;

        return $this;
    }

    public function output(Html2Pdf $pdf)
    {
        return $this->printer
            ->title($this->title)
            ->output($pdf);
    }

    public function test()
    {
        return $this->printer->test();
    }
}
