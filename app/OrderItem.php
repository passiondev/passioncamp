<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use App\Collections\OrderItemCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->attributes['type'] = $item->type;
        });
    }

    public function newCollection(array $models = [])
    {
        return new OrderItemCollection($models);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('canceled_at');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function canceledBy()
    {
        return $this->belongsTo(User::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getNameAttribute()
    {
        if ($this->item) {
            return $this->item->full_name;
        }

        return ucwords($this->type);
    }

    public function isOrganizationItem()
    {
        return ! is_null($this->org_type);
    }

    public function getIsCanceledAttribute()
    {
        return (bool) $this->canceled_at;
    }

    public function cancel()
    {
        $this->canceled_at = \Carbon\Carbon::now();
        $this->canceled_by_id = Auth::id();
        $this->save();

        return $this;
    }
}
