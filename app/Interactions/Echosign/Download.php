<?php

namespace App\Interactions\Echosign;

use Echosign\Agreements;
use Echosign\Transports\GuzzleTransport;
use Echosign\Creators\Reminder as ReminderCreator;

class Download extends BaseEchosignInteraction
{
    protected $agreements;

    public function __construct()
    {
        parent::__construct();

        $this->agreements = new Agreements($this->token, new GuzzleTransport);
    }

    public function handle($agreementId)
    {
        $response = $this->agreements->combinedDocument($agreementId);
        
        return $response->getBody();
    }
}
