<?php

namespace App;

use App\Ticket;
use Echosign\Agreements;
use Illuminate\Database\Eloquent\Model;
use Echosign\Transports\GuzzleTransport;

class Waiver extends Model
{
    protected $fillable = [
        'documentKey',
        'eventType',
        'status',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}