<?php

namespace App\Jobs\Waiver;

use Illuminate\Bus\Queueable;
use App\Contracts\EsignProvider;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateAgreementStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $agreementId;

    protected $requestBody;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($agreement, $requestBody)
    {
        $this->agreementId = $agreement->agreementId;
        $this->requestBody = $requestBody;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EsignProvider $provider)
    {
        $provider->updateAgreementStatus($this->agreementId, $this->requestBody);
    }
}
