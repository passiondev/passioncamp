<?php

namespace App\Exports;

class TicketExport extends Export
{
    private $tickets;
    private $includeAdditionalFields;

    public function __construct($tickets, $includeAdditionalFields)
    {
        $this->tickets = $tickets;
        $this->includeAdditionalFields = $includeAdditionalFields;
    }

    public function data()
    {
        return fractal($this->tickets, new Transformers\TicketTransformer($this->includeAdditionalFields))->toArray();
    }
}
