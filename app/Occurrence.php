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
            case 'staff2019':
                $price = $price * .8;
                break;
            case 'rising':
                if (now()->between(Carbon::parse('2019-01-16 05:00:00'), Carbon::parse('2019-01-20 23:59:59'))) {
                    $price = $price - 10;
                }
                break;
            case 'lovett':
                if (now()->between(Carbon::parse('2019-01-16 05:00:00'), Carbon::parse('2019-02-15 23:59:59'))) {
                    $price = $price - 20;
                }
                break;

            case 'jodi':
                $price = 5;
                break;
        }

        if ($this->config['slug'] == 'ww2019hs') {
            switch (strtolower($discountCode)) {
                case 'studentrep':
                case 'ahs':
                case 'dhs':
                case 'fcs':
                case 'gac':
                case 'grady':
                case 'landmark':
                case 'nahs':
                case 'pope':
                case 'skipstone':
                case 'northview':
                case 'cambridge':
                case 'woodstock':
                case 'harrison':
                case 'mtparan':
                case 'mtpisgah':
                case 'mtpisgah':
                case 'starsmill':
                case 'starrsmill':
                case 'kingsridge':
                case 'southpaulding':
                case 'roswell':
                case 'homeschool':
                    $price = $price - 10;
                    break;

                case 'ww2019':
                    if (now()->lt(Carbon::parse('2019-02-08 10:00:00'))) {
                        $price = $price - 10;
                    }
                    break;

                case '23':
                    if (now()->between(Carbon::parse('2019-01-16 05:00:00'), Carbon::parse('2019-01-17 23:59:59'))) {
                        $price = $price - 20;
                    }
                    break;

                case 'scholarship20':
                    if (now() < Carbon::parse('2019-02-15 23:59:59')) {
                        $price = $price - 20;
                    }
                    break;

                case 'scholarship45':
                    if (now() < Carbon::parse('2019-02-15 23:59:59')) {
                        $price = $price - 45;
                    }
                    break;

                case 'scholarship50':
                    if (now() < Carbon::parse('2019-02-15 23:59:59')) {
                        $price = $price - 50;
                    }
                    break;

                case 'scholarship80':
                    if (now() < Carbon::parse('2019-02-15 23:59:59')) {
                        $price = $price - 80;
                    }
                    break;

                case 'scholarship95':
                    if (now() < Carbon::parse('2019-02-15 23:59:59')) {
                        $price = $price - 95;
                    }
                    break;

                case 'scholarship120':
                    if (now() < Carbon::parse('2019-02-15 23:59:59')) {
                        $price = $price - 120;
                    }
                    break;

                case '175':
                    if (now() < Carbon::parse('2019-02-09 12:00:00')) {
                        $price = 175;
                    }
                    break;

                case 'sunday':
                    if (now() < Carbon::parse('2019-02-10 23:59:59')) {
                        $price = 185;
                    }
                    break;
            }
        }

        if ($this->config['slug'] == 'ww2019ms') {
            switch (strtolower($discountCode)) {
                case '23':
                    if (now()->between(Carbon::parse('2019-01-16 05:00:00'), Carbon::parse('2019-01-17 23:59:59'))) {
                        $price = $price - 31;
                    }
                    break;

                case 'studentrep':
                case 'fcs':
                case 'gac':
                case 'heritage':
                case 'landmark':
                case 'skipstone':
                    $price = $price - 10;
                    break;

                case 'scholarship30':
                    if (now() < Carbon::parse('2019-02-01 23:59:59')) {
                        $price = $price - 30;
                    }
                    break;

                case 'scholarship50':
                    if (now() < Carbon::parse('2019-02-01 23:59:59')) {
                        $price = $price - 50;
                    }
                    break;

                case 'scholarship55':
                    if (now() < Carbon::parse('2019-02-01 23:59:59')) {
                        $price = $price - 55;
                    }
                    break;

                case 'scholarship80':
                    if (now() < Carbon::parse('2019-02-01 23:59:59')) {
                        $price = $price - 80;
                    }
                    break;

                case 'scholarship100':
                    if (now() < Carbon::parse('2019-02-01 23:59:59')) {
                        $price = $price - 100;
                    }
                    break;

                case '175':
                    if (now() < Carbon::parse('2019-02-09 12:00:00')) {
                        $price = 175;
                    }
                    break;

                case 'sunday':
                    if (now() < Carbon::parse('2019-02-10 23:59:59')) {
                        $price = 185;
                    }
                    break;
            }
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
