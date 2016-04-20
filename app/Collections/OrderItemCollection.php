<?php

namespace App\Collections;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class OrderItemCollection extends Collection
{
    public function active()
    {
        return $this->filter(function ($item) {
            return ! $item->is_canceled;
        });
    }

    public function ofAgegroup($agegroup)
    {
        return $this->filter(function ($ticket) use ($agegroup) {
            return $ticket->agegroup == $agegroup;
        });
    }
}