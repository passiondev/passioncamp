<?php

namespace App\Printer;

use App\Jobs\PrintNodeJob;
use PrintNode\Entity\PrintJob;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PrintNodePrinter extends BasePrinter implements PrinterContract
{
    use DispatchesJobs;

    private $printer_id;
    private $printNode;

    public function __construct($printNode, $printer_id)
    {
        $this->printer_id = $printer_id;
        $this->printNode = $printNode;
    }

    public function getContent($pdf)
    {
        return base64_encode($pdf->Output($this->getFilename(), 'S'));
    }

    public function output($pdf)
    {
        $printJob = new PrintJob($this->printNode);
        $printJob->printer = $this->printer_id;
        $printJob->contentType = 'pdf_base64';
        $printJob->content = $this->getContent($pdf);
        $printJob->source = 'passioncamp';
        $printJob->title = $this->getTitle();
        $printJob->options = ['dpi' => '300'];

        $this->printNode->createPrintJob($printJob);
    }

    public function test()
    {
        $printJob = new PrintJob($this->printNode);
        $printJob->printer = $this->printer_id;
        $printJob->contentType = 'pdf_uri';
        $printJob->content = 'https://app.printnode.com/testpdfs/label_3in_x_1in_barcode.pdf';
        $printJob->source = 'passioncamp';
        $printJob->title = 'TEST';

        $this->printNode->createPrintJob($printJob);
    }
}