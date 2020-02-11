<?php

namespace App\Jobs\Ticket;

use Facades\App\Contracts\Printing\Factory as Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrintWristbandJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    public $ticket;
    public $printer;
    public $driver;

    /**
     * Create a new job instance.
     *
     * @param mixed      $ticket
     * @param mixed      $printer
     * @param mixed|null $driver
     */
    public function __construct($ticket, $printer, $driver = null)
    {
        $this->ticket = $ticket;
        $this->printer = $printer;
        $this->driver = $driver;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Printer::driver($this->driver)->print(
            $this->printer,
            url()->signedRoute('tickets.wristband.show', $this->ticket),
            [
                'title' => $this->ticket->name,
                'source' => 'PCC Check In',
            ]
        );
    }
}
