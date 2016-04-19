<?php

namespace App;

use Echosign\Agreements;
use Echosign\Transports\GuzzleTransport;

class Waiver
{
    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function create()
    {
        return;
    }

    public function status($update = true)
    {
        if ($update) {
            $this->updateStatus();
        }

        return $this->ticket->waiver_status;
    }

    public function getStatus()
    {
        $transport   = new GuzzleTransport();
        $agreement    = new Agreements( $this->token, $transport );
        $agreementInfo = $agreement->status($this->ticket->waiver_id);

        return $agreementInfo->getStatus();
    }
}