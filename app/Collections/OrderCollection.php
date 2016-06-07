<?php

namespace App\Collections;

class OrderCollection extends BaseCollection
{
    public function getTicketCountAttribute()
    {
        return $this->_sum('ticket_count');
    }

    public function getTicketTotalAttribute()
    {
        return $this->_sum('ticket_total');
    }

    public function getGrandTotalAttribute()
    {
        return $this->_sum('grand_total');
    }

    public function getDonationTotalAttribute()
    {
        return $this->_sum('donation_total');
    }

    public function getTransactionTotalAttribute()
    {
        return $this->_sum('transaction_total');
    }

    public function getBalanceAttribute()
    {
        return $this->_sum('balance');
    }

    public function getStudentCountAttribute()
    {
        return $this->_sum('student_count');
    }

    public function getLeaderCountAttribute()
    {
        return $this->_sum('leader_count');
    }

    private function _sum($key)
    {
        return $this->sum(function ($order) use ($key) {
            return $order->$key;
        });
    }


}