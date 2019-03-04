<?php

namespace App;

use Illuminate\Support\Carbon;

class Occurrence
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function ticketPrice($numTickets = 1, $discountCode = null)
    {
        $price = collect($this->pricing)
            ->filter(function ($price, $date) {
                return now()->gte(Carbon::parse($date)->endOfDay());
            })
            ->values()
            ->sort()
            ->last();

        $lowestTicketPriceInDollars = $this->lowestTicketPrice() / 100;

        if ($numTickets >= 3) {
            $price = $lowestTicketPriceInDollars;
        }

        switch (strtolower($discountCode)) {
            case 'jodi':
                $price = 5;
                break;
        }

        if ($numTickets >= 3 && $price < $lowestTicketPriceInDollars - 10) {
            // reset multi ticket discount if less than 10 off lowest price
            return $this->ticketPrice(1, $discountCode);
        }

        return $price * 100;
    }

    public function lowestTicketPrice()
    {
        return collect($this->pricing)->values()->sort()->first() * 100;
    }

    public function isClosed()
    {
        return now()->gte(Carbon::parse($this->closes));
    }

    public function __get($name)
    {
        return $this->config[$name] ?? null;
    }
}
