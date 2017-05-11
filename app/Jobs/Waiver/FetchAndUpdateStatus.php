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
    public $waiver;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($waiver)
    {
        $this->waiver = $waiver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $status = $this->waiver->fetchStatus();

        $this->waiver->fill([
            'status' => $status
        ])->touch();

        if ($status == WaiverStatus::COMPLETE) {
            $pdf = $this->waiver->fetchPdf();

            Storage::disk('dropbox')->put($this->waiver->dropboxFilePath(), $pdf);
        }
    }
}
