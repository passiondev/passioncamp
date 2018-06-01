<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Facades\App\Contracts\Printing\Factory as Printer;

class PrintRoomLabelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $room;

	protected $printer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($room, $printer)
    {
        $this->room = $room;
        $this->printer = $printer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Printer::print(
            $this->printer,
            url()->signedRoute('room.label.show', $this->room),
            [
                'title' => $this->room->name,
                'source' => $this->room->organization->church->name,
            ]
        );
    }
}
