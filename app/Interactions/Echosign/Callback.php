<?php

namespace App\Interactions\Echosign;

use Validator;
use App\Waiver;
use App\Repositories\WaiverRepository;

class Callback
{
    protected $waiver;

    public function __construct(WaiverRepository $waiver)
    {
        $this->waiver = $waiver;
    }

    public function validator(array $data)
    {
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
    }
}