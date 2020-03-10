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

    public function __get($name)
    {
        return $this->config[$name] ?? null;
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

        // multi ticket auto discount removed 3/6/19

        switch (strtolower($discountCode)) {
            // generic
            case 'staffcamp2020':
                $price = $price * .8;
            break;
            case 'pc2020':
                $price = $price - 20;
            break;
            case 'easter':
                if (now()->between(Carbon::parse('2020-04-11 00:00:00'), Carbon::parse('2020-04-12 23:59:59'))) {
                    $price = $price - 20;
                }
            break;
            case 'rising':
                if (now()->between(Carbon::parse('2020-04-29 00:00:00'), Carbon::parse('2020-05-01 23:59:59'))) {
                    $price = $price - 10;
                }
            break;
            case 'summer':
                if (now()->between(Carbon::parse('2020-05-22 00:00:00'), Carbon::parse('2020-05-23 23:59:59'))) {
                    $price = $price - 15;
                }
            break;

            // scholarships
            case 'scholarship50':
                $price = $price - 50;
            break;
            case 'scholarship100':
                $price = $price - 100;
            break;
            case 'scholarship150':
                $price = $price - 150;
            break;
            case 'scholarship185':
                $price = $price - 185;
            break;
            case 'scholarship200':
                $price = $price - 200;
            break;

            // schools
            case 'ahs20':
            case 'cambridge20':
            case 'carver20':
            case 'dhs20':
            case 'fcs20':
            case 'gac20':
            case 'grady20':
            case 'kingsridge20':
            case 'lakeside20':
            case 'landmark20':
            case 'maynard20':
            case 'mtpisgah20':
            case 'pope20':
            case 'wesleyan20':
            case 'whitefield20':
                $price = $price - 10;
            break;
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

    public function isSoldOut()
    {
        return true === $this->config['sold_out'];
    }
}
