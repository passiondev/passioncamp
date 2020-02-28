<?php

namespace App;

class Item extends Model
{
    protected $table = 'items';

    protected $guarded = [];

    public function getFullNameAttribute()
    {
        return trim(sprintf('%s %s', $this->name, $this->type == 'other' ?: ucfirst($this->type)));
    }

    public function scopeWithPurchasedSum($query)
    {
        $query->addSubSelect(
            'purchased_sum',
            OrderItem::selectRaw('ifnull(sum(quantity), 0)')->whereRaw('order_items.item_id = items.id')->whereNull('order_items.deleted_at')
        );
    }
}
