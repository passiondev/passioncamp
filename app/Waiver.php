<?php

namespace App;

use App\Ticket;
use Carbon\Carbon;
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

    public function getStatusAttribute($status)
    {
        return WaiverStatus::get($status);
    }

    public function canBeReminded()
    {
        // updated more than 24 hours ago
        return Carbon::now()->subHour(24)->gt($this->updated_at) && $this->status == WaiverStatus::PENDING;
    }
}
