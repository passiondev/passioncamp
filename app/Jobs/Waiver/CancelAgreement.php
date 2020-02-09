<?php

namespace App\Jobs\Waiver;

use Facades\App\Services\Esign\ProviderFactory as EsignProviderFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
