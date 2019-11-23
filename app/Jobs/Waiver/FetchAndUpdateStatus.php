<?php

namespace App\Jobs\Waiver;

use App\WaiverStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchAndUpdateStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    public $waiver;

    /**
     * Create a new job instance.
     *
     * @param mixed $waiver
     */
    public function __construct($waiver)
    {
        $this->waiver = $waiver;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $status = $this->waiver->fetchStatus();

        $this->waiver->fill([
            'status' => $status,
        ])->touch();

        if (WaiverStatus::COMPLETE == $status) {
            $pdf = $this->waiver->fetchPdf();

            Storage::disk('dropbox')->put($this->waiver->dropboxFilePath(), $pdf);
        }
    }
}
