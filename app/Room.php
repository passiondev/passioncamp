<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sofa\Revisionable\Revisionable;
use Sofa\Revisionable\Laravel\RevisionableTrait;

class Room extends Model implements Revisionable
{
    use SoftDeletes, RevisionableTrait;

    protected $table = 'room';

    protected $revisionable = ['name', 'description', 'notes'];

    public function isRevisioned()
    {
        return false;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
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

    public function revision()
    {
        // get fresh revision info if it hasnt been loaded
        if ( ! $this->relationLoaded('latestRevision')) {
            $this->load('latestRevision');
        }

        $logger = static::getRevisionableLogger();
        $table = $this->getTable();
        $id    = $this->getKey();
        $user  = Auth::user();
        $latest = $this->latestRevision ? $this->latestRevision->new : [];
        $current = $this->getNewAttributes();

        $logger->revisionLog('revision', $table, $id, $latest, $current, $user);

        // unset relation so that fresh revision info will be pulled 
        unset($this->relations['latestRevision']);
    }

    public function getHasChangedSinceLastRevisionAttribute()
    {
        return $this->latestRevision && ! empty($this->latestRevision->getDiff());
    }

}
