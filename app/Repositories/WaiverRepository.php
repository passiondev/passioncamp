<?php

namespace App\Repositories;

use Validator;
use App\Waiver;

class WaiverRepository
{
    public function update(Waiver $waiver, array $data)
    {
        $waiver->forceFill([
            'status' => $data['status'],
            'eventType' => $data['eventType'],
        ]);
        
        $waiver->save();
    }
}
