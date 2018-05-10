<?php

namespace App;

use Carbon\Carbon;
use App\Jobs\CancelSignatureRequest;
use Facades\App\Services\Esign\ProviderFactory as EsignProviderFactory;

class Waiver extends Model
{
    protected $guarded = [];

    protected $touches = ['ticket'];

    protected $attributes = [
        'status' => WaiverStatus::CREATED,
    ];

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($waiver) {
            $waiver->cancelSignatureRequest();
        });
    }

    public function scopeComplete($query)
    {
        return $query->where('status', WaiverStatus::COMPLETE);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getStatusAttribute($status)
    {
        return WaiverStatus::get($status);
    }

    public function setStatusAttribute($status)
    {
        $this->attributes['status'] = WaiverStatus::get($status);
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
            'Passion Camp 2018 Waivers',
            $this->ticket->owner->organization_id . ' - ' . $this->ticket->owner->organization->church->name,
            $this->ticket_id . ' - ' . $this->ticket->name,
        ]);
    }

    public function cancelSignatureRequest()
    {
        if (! $this->provider_agreement_id) {
            return;
        }

        dispatch(new CancelSignatureRequest($this->provider, $this->provider_agreement_id));
    }

    public function getIsCompleteAttribute()
    {
        return $this->isComplete();
    }
}
