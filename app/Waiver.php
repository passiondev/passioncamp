<?php

namespace App;

use App\Ticket;
use App\Observers\WaiverObserver;

class Waiver extends Model
{
    protected $guarded = [];

    protected $attributes = [
        'status' => WaiverStatus::CREATED,
    ];

    protected static function boot()
    {
        static::observe(WaiverObserver::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
