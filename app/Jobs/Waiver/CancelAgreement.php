<?php

namespace App\Jobs\Waiver;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Facades\App\Services\Esign\ProviderFactory as EsignProviderFactory;

class CancelAgreement implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $provider;

    protected $agreementId;

    /**
     * Create a new job instance.
     *
     * @param mixed $provider
     * @param mixed $agreementId
     */
    public function __construct($provider, $agreementId)
    {
        $this->provider = $provider;
        $this->agreementId = $agreementId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        EsignProviderFactory::make($this->provider)->cancelSignatureRequest($this->agreementId);
    }
}
