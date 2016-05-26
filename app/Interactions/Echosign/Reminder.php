<?php

namespace App\Interactions\Echosign;

use Echosign\Creators\Reminder as ReminderCreator;

class Reminder extends BaseEchosignInteraction
{
    protected $reminder_creator;

    public function __construct()
    {
        parent::__construct();

        $this->reminder_creator = new ReminderCreator($this->token);
    }

    public function create($agreementId)
    {
        return $this->reminder_creator->create($agreementId);
    }
}
