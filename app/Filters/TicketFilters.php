<?php

namespace App\Filters;

class TicketFilters extends Filters
{
    protected $filters = ['organization'];

    protected function organization($organization_id)
    {
        return $this->builder->select('order_items.*')->join('orders', function ($q) {
            $q->whereRaw('owner_id = orders.id AND owner_type = "App\\\Order"');
        })->where('orders.organization_id', $organization_id);
    }
}
