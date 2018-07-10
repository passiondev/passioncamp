<?php

namespace App\Jobs\Waiver;

use Illuminate\Bus\Queueable;
use App\Contracts\EsignProvider;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CancelAgreement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $agreementId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agreement)
    {
        $this->agreementId = $agreement->agreementId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EsignProvider $provider)
    {
        $provider->cancelSignatureRequest($this->agreementId);
    }
}
