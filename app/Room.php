<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
