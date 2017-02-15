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

    public function unassigendSort()
    {
        return $this->sortBy(function ($ticket, $key) {
            return sprintf(
                "%02d__%s__%d__%s__%s",
                $ticket->person->grade == 0 ? 99 : $ticket->person->grade,
                $ticket->person->gender == 'M' ? 1 : -1,
                $ticket->agegroup == 'leader' ? 1 : -1,
                $ticket->person->first_name,
                $ticket->person->last_name
            );
        });
    }

    public function assigendSort()
    {
        return $this->sortBy(function ($ticket, $key) {
            return sprintf(
                "%s__%02d__%d__%s__%s",
                $ticket->agegroup == 'leader' ? -1 : 1,
                $ticket->person->grade == 0 ? 99 : $ticket->person->grade,
                $ticket->person->gender == 'M' ? 1 : -1,
                $ticket->person->first_name,
                $ticket->person->last_name
            );
        });
    }

    public function checkedIn()
    {
        return $this->filter(function ($ticket) {
            return $ticket->is_checked_in;
        });
    }
}
