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

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($waiver) {
            $waiver->provider()->cancelSignatureRequest($waiver->provider_agreement_id);
        });
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

    public function isComplete()
    {
        return $this->status == WaiverStatus::COMPLETE;
    }

    public function provider()
    {
        return EsignProviderFactory::make($this->provider);
    }

    public function fetchStatus()
    {
        $status = $this->provider()->fetchStatus($this->provider_agreement_id);

        return WaiverStatus::get($status);
    }

    public function fetchPdf()
    {
        return $this->provider()->fetchPdf($this->provider_agreement_id);
    }

    public function dropboxFilePath()
    {
        return vsprintf('%s/%s/%s.pdf', [
            'Passion Camp 2017 Waivers',
            $this->ticket->order->organization_id . ' - ' . $this->ticket->order->organization->church->name,
            $this->ticket_id . ' - ' . $this->ticket->name,
        ]);
    }
}
