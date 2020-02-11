<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

abstract class BaseCollection extends Collection
{
    public function __get($key)
    {
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key);
        }
    }

    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Str::studly($key).'Attribute');
    }

    protected function mutateAttribute($key)
    {
        return $this->{'get'.Str::studly($key).'Attribute'}();
    }
}
