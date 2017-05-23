<?php

namespace App\Filters;

class RoomFilters extends Filters
{
    protected $filters = ['organization', 'hotel'];

    protected function organization($organization_id)
    {
        return $this->builder->where('organization_id', $organization_id);
    }

    protected function hotel($hotel_id)
    {
        return $this->builder->where('hotel_id', $hotel_id);
    }
}
