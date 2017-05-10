<?php

namespace App\Jobs\Waiver;

use App\WaiverStatus;
use Illuminate\Bus\Queueable;
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
        $agreementId = $this->waiver->provider()->createSignatureRequest([
            'documentCreationInfo' => [
                'fileInfos' => [
                    'libraryDocumentId' => '3AAABLblqZhBbAFBkT6dWSNEEUttbb8Ck4bFUPCbOUpzG8hndrlCqudK8p57OdW47Tg1PGnMEdjp55Lkw36GIBElm5QwCx00c'
                ],
                'name' => 'Passion Camp 2017 Waiver & Release',
                'message' => 'Please note that each participant must have a signed waiver completed by May 29.',
                'signatureType' => 'ESIGN',
                'recipientSetInfos' => [
                    'recipientSetMemberInfos' => [
                        'email' => $this->waiver->ticket->order->user->person->email
                    ],
                    'recipientSetRole' => ['SIGNER']
                ],
                'mergeFieldInfo' => [
                    [
                        'fieldName' => 'Church Name',
                        'defaultValue' => $this->waiver->ticket->order->organization->church->name
                    ],
                    [
                        'fieldName' => 'Church Location',
                        'defaultValue' => $this->waiver->ticket->order->organization->church->location
                    ],
                    [
                        'fieldName' => 'Participant Name',
                        'defaultValue' => $this->waiver->ticket->name
                    ],
                    [
                        'fieldName' => 'Participant Name 2',
                        'defaultValue' => $this->waiver->ticket->name
                    ],
                    [
                        'fieldName' => 'Participant Name 3',
                        'defaultValue' => $this->waiver->ticket->name
                    ],
                    [
                        'fieldName' => 'Participant Name 4',
                        'defaultValue' => $this->waiver->ticket->name
                    ],
                    [
                        'fieldName' => 'Participant Gender',
                        'defaultValue' => $this->waiver->ticket->gender == 'M' ? 'Male' : 'Female'
                    ],
                    [
                        'fieldName' => 'Parent Name',
                        'defaultValue' => $this->waiver->ticket->order->user->person->name
                    ],
                    [
                        'fieldName' => 'Parent Name 2',
                        'defaultValue' => $this->waiver->ticket->order->user->person->name
                    ],
                    [
                        'fieldName' => 'Parent Name 3',
                        'defaultValue' => $this->waiver->ticket->order->user->person->name
                    ],
                    [
                        'fieldName' => 'Parent Phone',
                        'defaultValue' => $this->waiver->ticket->order->user->person->phone
                    ],
                    [
                        'fieldName' => 'Parent Email',
                        'defaultValue' => $this->waiver->ticket->order->user->person->email
                    ],
                ],
                'signatureFlow' => 'SENDER_SIGNATURE_NOT_REQUIRED',
                'callbackInfo' => action('Webhooks\AdobeSignController'),
            ]
        ]);

        $this->waiver->update([
            'provider_agreement_id' => $agreementId,
            'status' => WaiverStatus::PENDING,
        ]);
    }
}
