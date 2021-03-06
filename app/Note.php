<?php

namespace App;

class Note extends Model
{
    protected $fillable = ['body'];

    protected $with = ['author'];

    public function notated()
    {
        return $this->morphTo();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
