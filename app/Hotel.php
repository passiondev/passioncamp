<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class Hotel extends Item
{
    protected $attributes = [
        'type' => 'hotel',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('items.type', '=', 'hotel');
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
        return $this->morphedByMany(Organization::class, 'owner', 'order_items', 'item_id')
            ->withPivot('quantity')
            ->where('quantity', '>', '0')
            ->distinct();
    }

    public function scopeWithDistinctOrganizationsCount($query)
    {
        $query->addSubSelect(
            'organizations_count',
            Organization::selectRaw('count(distinct organizations.id)')
                ->join('order_items', 'organizations.id', 'organization_id')
                ->where('quantity', '>', '0')
                ->whereRaw('items.id = item_id')
        );
    }
}
