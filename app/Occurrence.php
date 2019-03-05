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

        // return scholarship code price before checking for lowest price
        switch (strtolower($discountCode)) {
            // case 'scholarship':
            //     return 5 * 100;
            //     break;
        }

        $lowestTicketPriceInDollars = $this->lowestTicketPrice() / 100;

        if ($numTickets >= 2) {
            $price = $lowestTicketPriceInDollars;
        }

        switch (strtolower($discountCode)) {
            // case 'discount':
            //     $price -= 10;
            //     break;
        }

        if ($numTickets >= 2 && $price < $lowestTicketPriceInDollars - 10) {
            // remove discount code if less than 10 off lowest price
            return $this->ticketPrice($numTickets);
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
