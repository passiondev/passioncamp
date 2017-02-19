<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Laravel\Revisionable;

class Room extends Model
{
    use SoftDeletes, Revisionable;

    protected $revisionable = ['name', 'description', 'notes', 'hotel_id'];

    protected $fillable = [
        'name',
        'description',
        'notes',
        'capacity',
        'roomnumber',
        'confirmation_number',
        'is_checked_in',
        'is_key_received',
    ];

    protected $dates = [
        'key_received_at',
        'checked_in_at',
    ];

    public function isRevisioned()
    {
        return false;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Item::class, 'hotel_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->active();
    }

    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: Auth::user();

        if ($user->isSuperAdmin()) {
            return $query;
        }

        return $query->where('organization_id', $user->organization_id);
    }

    public function getAssignedAttribute()
    {
        return $this->tickets->count();
    }

    public function getCapacityAttribute($capacity)
    {
        return number_format($capacity);
    }

    public function getIsAtCapacityAttribute()
    {
        return (bool) ! $this->remaining;
    }

    public function getRemainingAttribute()
    {
        return $this->capacity - $this->assigned;
    }

    public function getHotelNameAttribute()
    {
        return $this->hotel ? $this->hotel->name : '';
    }

    public function revision()
    {
        // get fresh revision info if it hasnt been loaded
        if (! $this->relationLoaded('latestRevision')) {
            $this->load('latestRevision');
        }

        $logger = static::getRevisionableLogger();
        $table = $this->getTable();
        $id    = $this->getKey();
        $user  = Auth::user();
        $latest = $this->latestRevision ? $this->latestRevision->new : [];
        $current = $this->getNewAttributes() + ['hotel' => $this->hotel_name];

        $logger->revisionLog('revision', $table, $id, $latest, $current, $user);

        // unset relation so that fresh revision info will be pulled
        unset($this->relations['latestRevision']);
    }

    public function getHasChangedSinceLastRevisionAttribute()
    {
        return $this->latestRevision && ! empty($this->latestRevision->getDiff());
    }

    public function getIsKeyReceivedAttribute()
    {
        return (bool) $this->key_received_at;
    }

    public function getIsCheckedInAttribute()
    {
        return (bool) $this->checked_in_at;
    }

    public function setIsCheckedInAttribute($is_checked_in)
    {
        $this->checked_in_at = $is_checked_in ? \Carbon\Carbon::now() : null;
    }

    public function setIsKeyReceivedAttribute($is_key_received)
    {
        $this->key_received_at = $is_key_received ? \Carbon\Carbon::now() : null;
    }

    public function checkIn()
    {
        $this->checked_in_at = \Carbon\Carbon::now();
        $this->save();
    }

    public function keyReceived()
    {
        $this->key_received_at = \Carbon\Carbon::now();
        $this->save();
    }
}
