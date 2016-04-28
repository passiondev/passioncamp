<?php

namespace App\Repositories;

use App\Waiver;
use GrahamCampbell\Flysystem\Facades\Flysystem;

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

    public function download(Waiver $waiver, $document_body)
    {
        $response = Flysystem::connection('dropbox')->put("{$waiver->ticket->organization->id} - {$waiver->ticket->organization->church->name}/{$waiver->ticket->id} - {$waiver->ticket->person->name}.pdf", (string) $document_body);
    }
}
