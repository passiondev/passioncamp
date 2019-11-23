<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Notifications\ExportCompleted;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;

    protected $version;

    public function __construct($user, $version)
    {
        $this->user = $user;
        $this->version = $version;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->user->notify(new ExportCompleted($this->version));
    }
}
