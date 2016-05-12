<?php

namespace App;

use App\Ticket;
use Echosign\Agreements;
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

    public function getStatusAttribute($status)
    {
        switch ($status) {
            case '':
            case 'OUT_FOR_SIGNATURE':
                return 'pending';
            
            default:
                return strtolower(str_replace('_', ' ', $status));
        }
    }
}
