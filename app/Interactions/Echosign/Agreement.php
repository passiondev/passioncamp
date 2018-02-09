<?php

namespace App\Interactions\Echosign;

use App\Ticket;
use Echosign\Agreements;
use Echosign\Creators\Agreement as BaseEchosignInteraction;
use Echosign\Transports\GuzzleTransport;
use Echosign\RequestBuilders\Agreement\FileInfo;
use Echosign\RequestBuilders\Agreement\MergefieldInfo;
use Echosign\RequestBuilders\AgreementStatusUpdateInfo;
use Echosign\RequestBuilders\Agreement\DocumentCreationInfo;

class Agreement extends BaseEchosignInteraction
{
    protected $message = null;
    protected $libraryDocumentId = '3AAABLblqZhAscwAuVs2hZJSpI8O8DhgUHc_6JjXx13uqixcssy_1qCK3EP_NLP75sZvHZyVOmIVjKXkM-B2_lEJ6EBPHA0q_';
    protected $agreementName = 'Passion Camp Waiver';

    public function create($to, $data)
    {
        $mergefieldInfos = [
            new MergefieldInfo(array_get($data, 'name'), 'Custom Field 1'),
            new MergefieldInfo(array_get($data, 'church'), 'Custom Field 11'),
            new MergefieldInfo(array_get($data, 'location'), 'Custom Field 12'),
            new MergefieldInfo(ucwords(array_get($data, 'agegroup')), 'Drop Down 3'),
        ];

        $fileInfo = new FileInfo;
        $fileInfo->setLibraryDocumentId($this->libraryDocumentId);

        $documentCreationInfo = new DocumentCreationInfo($fileInfo, $this->agreementName, 'ESIGN', 'SENDER_SIGNATURE_NOT_REQUIRED');
        $documentCreationInfo->setCallBackInfo(route(config('services.echosign.callback')));

        $agreementCreator = new Agreement($this->token);

        try {
            $agreementId = $agreementCreator
                ->setMergeFieldInfos($mergefieldInfos)
                ->setDocumentCreationInfo($documentCreationInfo)
                ->send($fileInfo, $this->agreementName, $this->message, $to);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $agreementId;
    }

    public function getStatus($agreementId)
    {
        $transport   = new GuzzleTransport();
        $agreement    = new Agreements($this->token, $transport);
        $agreementInfo = $agreement->status($agreementId);

        return $agreementInfo->getStatus();
    }

    public function cancel($agreementId)
    {
        $transport   = new GuzzleTransport();
        $agreement    = new Agreements($this->token, $transport);
        $agreementInfo = $agreement->cancel($agreementId, new AgreementStatusUpdateInfo);
    }
}
