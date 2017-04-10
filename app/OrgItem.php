<?php

namespace App;

use App\Events\OrgItemSaved;
use Illuminate\Support\Facades\Auth;
use App\Collections\OrderItemCollection;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgItem extends OrderItem
{
    protected $table = 'order_items';

    protected $events = [
        'saved' => OrgItemSaved::class,
    ];
}
