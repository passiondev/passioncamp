<?php

namespace App;

class Item extends Model
{
    protected $table = 'items';

    public function getFullNameAttribute()
    {
        return sprintf('%s %s', $this->name, ucfirst($this->type));
    }

    public function scopeWithPurchasedSum($query)
    {
        $query->addSubSelect(
            'purchased_sum',
            OrderItem::selectRaw('sum(quantity)')->whereRaw('order_items.item_id = items.id')
        );
    }
}
