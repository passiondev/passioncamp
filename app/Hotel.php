<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class Hotel extends Item
{
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
        return $this->belongsToMany(Organization::class, 'order_items', 'item_id', 'organization_id')->wherePivot('quantity', '>', '0');
    }

    public function getRegisteredCountAttribute()
    {
        return number_format($this->items->sum('quantity'));
    }

    public function getCapacityAttribute($capacity)
    {
        return number_format($capacity);
    }

    public function getRemainingCountAttribute()
    {
        return $this->capacity - $this->registered_count;
    }
}
