<?php

namespace App\Jobs\Waiver\AdobeSign;

use App\WaiverStatus;
use Illuminate\Bus\Queueable;
use KevinEm\AdobeSign\AdobeSign;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RequestWaiverSignature implements ShouldQueue
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
        $agreement = $this->createAgreement();

        $waiver->update([
            'provider_agreement_id' => $agreement['agreementId'],
            'status' => WaiverStatus::PENDING,
        ]);
    }

    private function createAgreement()
    {
        $adobesign->createAgreement([
            'documentCreationInfo' => [
                'fileInfos' => [
                    'libraryDocumentId' => '3AAABLblqZhBH0HWWqwG-o0C6ueCVbKH3RPHKq1KbD7S_GtgiLBrKZuP5rybf8NYSaohATtL7BtWTgiweA9YB98sLBdvl7OT5'
                ],
                'name' => 'Passion Camp Waiver',
                'signatureType' => 'ESIGN',
                'recipientSetInfos' => [
                    'recipientSetMemberInfos' => [
                        'email' => $this->waiver->ticket->order->user->person->email
                    ],
                    'recipientSetRole' => ['SIGNER']
                ],
                'mergeFieldInfo' => [
                    [
                        'fieldName' => 'Custom Field 1',
                        'defaultValue' => $this->waiver->ticket->name
                    ]
                ],
                'signatureFlow' => 'SENDER_SIGNATURE_NOT_REQUIRED'
            ]
        ]);
    }
}
