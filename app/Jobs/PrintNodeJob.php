<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use PrintNode\Entity\PrintJob;
use PrintNode\Client as PrintNode;

class PrintNodeJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $printer_id;
    private $title;
    private $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($printer_id, $title, $content)
    {
        $this->printer_id = $printer_id;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PrintNode $printnode)
    {
        $printJob = new PrintJob($printnode);
        $printJob->printer = $this->printer_id;
        $printJob->contentType = 'pdf_base64';
        $printJob->content = $this->content;
        $printJob->source = 'passioncamp';
        $printJob->title = $this->title;
        $printJob->options = ['dpi' => '300'];

        $printnode->createPrintJob($printJob);
    }
}
