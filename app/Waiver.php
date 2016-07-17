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

    public function scopeActive($query)
    {
        return $query->whereNull('canceled_at');
    }

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

    public function getIsCompleteAttribute()
    {
        return $this->status == 'signed';
    }

    public function cancel()
    {
        $this->canceled_at = \Carbon\Carbon::now();
        $this->canceled_by_id = \Auth::id();
        $this->save();

        return $this;
    }

    public function complete()
    {
        $this->status = 'SIGNED';
        $this->eventType = 'MANUAL_ENTRY';
        $this->save();
    }
}
