<?php

namespace App\Presenters\Revisions;

use Sofa\Revisionable\Laravel\Presenter;

class Ticket extends Presenter {
    protected $passThrough = [
        'room_id'        => 'room',
    ];
}
