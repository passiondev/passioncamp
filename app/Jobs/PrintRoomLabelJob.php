<?php

namespace App\Jobs;

use Facades\App\Contracts\Printing\Factory as Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class PrintRoomLabelJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $room;

    protected $printer;

    /**
     * Create a new job instance.
     *
     * @param mixed $room
     * @param mixed $printer
     */
    public function __construct($room, $printer)
    {
        $this->room = $room;
        $this->printer = $printer;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Redis::throttle('printnode')->allow(10)->every(1)->then(function () {
            Printer::print(
                $this->printer,
                url()->signedRoute('room.label.show', $this->room),
                [
                    'title' => $this->room->name,
                    'source' => $this->room->organization->church->name,
                ]
            );
        }, function () {
            return $this->release(10);
        });
    }
}
