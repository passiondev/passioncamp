<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class OrganizationCollection extends Collection
{
    public function sumTicketQuantity()
    {
        return $this->sum(function ($organization) {
            return $organization->tickets->sum('quantity');
        });
    }
}