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
        $query->selectSub("
                SELECT SUM(quantity)
                FROM order_items
                WHERE order_items.item_id = items.id and order_items.deleted_at IS NULL
            ", 'purchased_sum'
        );
    }

}
