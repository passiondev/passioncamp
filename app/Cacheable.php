<?php

namespace App;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    public function cacheKey()
    {
        return sprintf(
            '%s/%s-%s',
            $this->getTable(),
            $this->getKey(),
            $this->updated_at->timestamp
        );
    }

    protected function cached($attribute)
    {
        return Cache::remember($this->cacheKey().':'.$attribute, 15, function () use ($attribute) {
            return $this->$attribute;
        });
    }
}
