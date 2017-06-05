<?php

namespace App\Jobs\Ticket;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Facades\App\Contracts\Printing\Factory as Printer;

class PrintWristbandJob implements ShouldQueue
{
    public $ticket;
    public $printer;
    public $driver;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ticket, $printer, $driver)
    {
        $this->ticket = $ticket;
        $this->printer = $printer;
        $this->driver = $driver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Printer::driver($this->driver)->print(
            $this->printer,
            action('TicketWristbandsController@signedShow', $this->ticket->toRouteSignatureArray()),
            [
                'title' => $this->ticket->name,
                'source' => 'PCC Check In'
            ]
        );
    }
}
