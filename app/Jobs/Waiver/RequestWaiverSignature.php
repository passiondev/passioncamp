<?php

namespace App\Jobs\Waiver;

use App\WaiverStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;

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
        try {
            $agreementId = $this->waiver->provider()->createSignatureRequest(
                $this->waiver->ticket->toHelloSignSignatureRequest()
            )->signature_request_id;
        } catch (\Exception $e) {
            report($e);
            $this->waiver->delete();
            throw $e;
        }

        $this->waiver->update([
            'provider_agreement_id' => $agreementId,
            'status' => WaiverStatus::PENDING,
        ]);
    }

    private function getLibraryDocumentId()
    {
        return $this->waiver->ticket->order->organization->slug == 'pcc'
            ? config('passioncamp.pcc_waiver_document_id')
            : config('passioncamp.waiver_document_id');
    }

    public function getRecipientEmail()
    {
        return App::environment('production')
            ? $this->waiver->ticket->order->user->person->email
            : 'matt.floyd+waivers@268generation.com';
    }

    public function toAdobeSignSignatureRequestArray()
    {
        return [
            'documentCreationInfo' => [
                'fileInfos' => [
                    'libraryDocumentId' => $this->getLibraryDocumentId(),
                ],
                'name' => __('waivers.request_subject'),
                'message' => __('waivers.request_body'),
                'signatureType' => 'ESIGN',
                'recipientSetInfos' => [
                    'recipientSetMemberInfos' => [
                        'email' => $this->getRecipientEmail(),
                    ],
                    'recipientSetRole' => ['SIGNER'],
                ],
                'mergeFieldInfo' => [
                    [
                        'fieldName' => 'Church Name',
                        'defaultValue' => $this->waiver->ticket->order->organization->church->name,
                    ],
                    [
                        'fieldName' => 'Church Location',
                        'defaultValue' => $this->waiver->ticket->order->organization->church->location,
                    ],
                    [
                        'fieldName' => 'Participant Name',
                        'defaultValue' => $this->waiver->ticket->name,
                    ],
                    [
                        'fieldName' => 'Participant Name 2',
                        'defaultValue' => $this->waiver->ticket->name,
                    ],
                    [
                        'fieldName' => 'Participant Name 3',
                        'defaultValue' => $this->waiver->ticket->name,
                    ],
                    [
                        'fieldName' => 'Participant Name 4',
                        'defaultValue' => $this->waiver->ticket->name,
                    ],
                    [
                        'fieldName' => 'Participant Birthdate',
                        'defaultValue' => $this->waiver->ticket->birthdate ?? '',
                    ],
                    [
                        'fieldName' => 'Participant Gender',
                        'defaultValue' => $this->waiver->ticket->person->gender == 'M' ? 'Male' : 'Female',
                    ],
                    [
                        'fieldName' => 'Parent Name',
                        'defaultValue' => $this->waiver->ticket->order->user->person->name,
                    ],
                    [
                        'fieldName' => 'Parent Name 2',
                        'defaultValue' => $this->waiver->ticket->order->user->person->name,
                    ],
                    [
                        'fieldName' => 'Parent Name 3',
                        'defaultValue' => $this->waiver->ticket->order->user->person->name,
                    ],
                    [
                        'fieldName' => 'Parent Phone',
                        'defaultValue' => $this->waiver->ticket->order->user->person->phone,
                    ],
                    [
                        'fieldName' => 'Parent Email',
                        'defaultValue' => $this->waiver->ticket->order->user->person->email,
                    ],
                ],
                'signatureFlow' => 'SENDER_SIGNATURE_NOT_REQUIRED',
                'callbackInfo' => action('Webhooks\AdobeSignController'),
            ],
        ];
    }
}
