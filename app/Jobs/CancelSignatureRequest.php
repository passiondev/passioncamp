<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Facades\App\Services\Esign\ProviderFactory as EsignProviderFactory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CancelSignatureRequest implements ShouldQueue
{
    public $provider;
    public $provider_agreement_id;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($provider, $provider_agreement_id)
    {
        $this->provider = $provider;
        $this->provider_agreement_id = $provider_agreement_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        EsignProviderFactory::make($this->provider)->cancelSignatureRequest($this->provider_agreement_id);
    }
}
