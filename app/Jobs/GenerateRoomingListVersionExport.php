<?php

namespace App\Jobs;

use App\Exports\RoomingListVersionExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateRoomingListVersionExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        (new RoomingListVersionExport($this->version))->store($this->version->file_name, 'exports');
    }
}
