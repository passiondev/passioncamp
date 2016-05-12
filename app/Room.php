<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $table = 'room';

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopeForUser($query, $user = null)
    {
        $user = $user ?: Auth::user();

        if ($user->is_super_admin) {
            return $query;
        }

        return $query->where('organization_id', $user->organization_id);
    }

    public function getAssignedAttribute()
    {
        return $this->tickets()->active()->count();
    }

    public function getCapacityAttribute($capacity)
    {
        return number_format($capacity);
    }
}
