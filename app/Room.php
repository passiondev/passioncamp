<?php

namespace App;

use App\Events\RoomDeleted;
use Illuminate\Support\Facades\Auth;
use Sofa\Revisionable\Laravel\Revisionable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $revisionable = ['name', 'description', 'notes', 'hotel_id'];

    protected $guarded = [];

    protected $dates = [
        'key_received_at',
        'checked_in_at',
    ];

    protected $events = [
        'deleted' => RoomDeleted::class,
    ];

    protected $attributes = [
        'capacity' => 4,
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
        return $this->belongsToMany(Ticket::class, 'room_assignments')->withTimestamps()->using(RoomAssignment::class);
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
        return;
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
