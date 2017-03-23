<?php

namespace App;

class Item extends Model
{
    public function getFullNameAttribute()
    {
        return sprintf('%s %s', $this->name, ucfirst($this->type));
    }
}
