<?php

namespace App\Collections;

class OrganizationSettingsCollection extends BaseCollection
{
    public function exists($key)
    {
        $keys = $this->map(function ($item) {
            return $item->key;
        });

        return collect($keys)->contains($key);
    }
}
