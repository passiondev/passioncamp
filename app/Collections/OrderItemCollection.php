<?php

namespace App\Collections;

use Illuminate\Database\Eloquent\Collection;

class OrderItemCollection extends Collection
{
    public function active()
    {
        return $this->filter(function ($item) {
            return !$item->is_canceled;
        });
    }

    public function ofAgegroup($agegroup)
    {
        return $this->filter(function ($ticket) use ($agegroup) {
            return $ticket->agegroup == $agegroup;
        });
    }

    public function unassigendSort()
    {
        return $this->sortBy(function ($ticket, $key) {
            return vsprintf('%02d__%s__%d__%s__%s', [
                0 == $ticket->person->grade ? 99 : $ticket->person->grade,
                'M' == $ticket->person->gender ? 1 : -1,
                'leader' == $ticket->agegroup ? 1 : -1,
                $ticket->person->first_name,
                $ticket->person->last_name,
            ]);
        });
    }

    public function assigendSort()
    {
        return $this->sortBy(function ($ticket, $key) {
            return sprintf(
                '%s__%02d__%d__%s__%s',
                'leader' == $ticket->agegroup ? -1 : 1,
                0 == $ticket->person->grade ? 99 : $ticket->person->grade,
                'M' == $ticket->person->gender ? 1 : -1,
                $ticket->person->first_name,
                $ticket->person->last_name
            );
        });
    }

    public function alphaSort()
    {
        return $this->sortBy('first_name')->sortBy('last_name');
    }

    public function checkedIn()
    {
        return $this->filter(function ($ticket) {
            return $ticket->is_checked_in;
        });
    }
}
