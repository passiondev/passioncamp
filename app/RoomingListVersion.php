<?php

namespace App;

class RoomingListVersion extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFileNameAttribute()
    {
        return 'Rooming List Export - Version #'.$this->id.'.xlsx';
    }
}
