<?php

namespace App\Filters;

class TicketFilters extends Filters
{
    protected $filters = ['organization'];

    protected function organization($organization_id)
    {
        return $this->builder->select('order_items.*')->join('orders', 'orders.id', '=', 'order_id')->where('orders.organization_id', $organization_id);
    }
}
