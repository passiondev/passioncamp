<?php

namespace App\Jobs\Waiver;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendReminder implements ShouldQueue
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
        $signatureRequest = $this->waiver->provider()->getSignatureRequest($this->waiver->provider_agreement_id);

        $signer = collect($signatureRequest->getSignatures())->first();

        if ($signer->getSignerEmail() != $this->waiver->ticket->waiver_signer_email) {
            $this->waiver->provider()
                ->updateSignatureRequest($this->waiver->provider_agreement_id, $signer->getId(), $this->waiver->ticket->waiver_signer_email);
        }

        $this->waiver->provider()
            ->sendReminder($this->waiver->provider_agreement_id, $this->waiver->ticket->waiver_signer_email);

        $this->waiver->touch();
    }
}
