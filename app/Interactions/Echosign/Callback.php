<?php

namespace App\Interactions\Echosign;

use Validator;
use App\Waiver;
use App\Events\Waivers\EmailBounced;
use App\Repositories\WaiverRepository;
use App\Interactions\Echosign\Download;

class Callback
{
    protected $waiver;
    protected $download;

    public function __construct(WaiverRepository $waiver, Download $download)
    {
        $this->waiver = $waiver;
        $this->download = $download;
    }

    public function validator(array $data)
    {
        \Log::info($data);
        return Validator::make($data, [
            'eventType' => 'required',
            'status' => 'required',
            'documentKey' => 'required',
        ]);
    }

    public function handle(array $data)
    {
        $waiver = Waiver::where('documentKey', $data['documentKey'])->firstOrFail();

        $this->waiver->update($waiver, $data);

        switch ($data['eventType']) {
            case 'EMAIL_BOUNCED':
                event(new EmailBounced($waiver));
                break;
        }

        switch ($data['status']) {
            case 'SIGNED':
                $this->waiver->download(
                    $waiver, 
                    $this->download->handle($data['documentKey'])
                );
                break;
        }
    }
}