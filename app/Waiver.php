<?php

namespace App;

use App\Ticket;
use Carbon\Carbon;
use App\Observers\WaiverObserver;
use Facades\App\Services\Esign\ProviderFactory as EsignProviderFactory;

class Waiver extends Model
{
    protected $guarded = [];

    protected $attributes = [
        'status' => WaiverStatus::CREATED,
    ];

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

    public function provider()
    {
        return EsignProviderFactory::make($this->provider);
    }
}
