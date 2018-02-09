<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class Hotel extends Item
{
    protected $casts = [
        'registered_sum' => 'int',
    ];

    protected $appends = [
        'remaining_count'
    ];

    protected $attributes = [
        'type' => 'hotel',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', '=', 'hotel');
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'order_items', 'item_id')->withPivot('quantity')->where('quantity', '>', '0');
    }

    public function scopeWithRegisteredSum($query)
    {
        $query->selectSub("
                SELECT SUM(quantity)
                FROM order_items
                WHERE order_items.item_id = items.id
            ", 'registered_sum');
    }

    public function getRemainingCountAttribute()
    {
        return $this->capacity - $this->registered_sum;
    }
}
