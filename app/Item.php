<?php

namespace App;

class Item extends Model
{
    protected $table = 'items';

    public function getFullNameAttribute()
    {
        return sprintf('%s %s', $this->name, ucfirst($this->type));
    }
}
