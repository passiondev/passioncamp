<?php

namespace App;

class RoomingListVersion extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
