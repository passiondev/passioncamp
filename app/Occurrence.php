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

        // multi ticket auto discount removed 3/6/19

        switch (strtolower($discountCode)) {
            // generic
            case 'staff2019':
                $price = $price * .8;
                break;
            case 'pc2019':
                $price = $price - 20;
                break;
            case 'trickshot':
                $price = $price - 15;
                break;
                
                break;
            case 'easter':
                if (now()->between(Carbon::parse('2019-04-19 00:00:00'), Carbon::parse('2019-04-21 23:59:59'))) {
                    $price = $price - 15;
                }
                break;
            case 'rising':
                if (now()->between(Carbon::parse('2019-04-24 05:00:00'), Carbon::parse('2019-04-26 23:59:59'))) {
                    $price = $price - 15;
                }
                break;
            case 'tuesday':
                if (now()->between(Carbon::parse('2019-05-14 00:00:00'), Carbon::parse('2019-05-14 23:59:59'))) {
                    $price = $price - 15;
                }
                break;
            case 'seniors':
                if (now()->between(Carbon::parse('2019-05-19 00:00:00'), Carbon::parse('2019-05-19 23:59:59'))) {
                    $price = $price - 15;
                }
                break;
            case 'summer':
                if (now()->between(Carbon::parse('2019-05-24 00:00:00'), Carbon::parse('2019-05-27 23:59:59'))) {
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
            case 'ahs19':
            case 'cambridge19':
            case 'dhs19':
            case 'fcs19':
            case 'gac19':
            case 'grady19':
            case 'kingsridge19':
            case 'lakeside19':
            case 'landmark19':
            case 'mtpisgah19':
            case 'pope19':
            case 'wesleyan19':
            case 'whitefield19':
                $price = $price - 10;
                break;

            // student rep
            case 'studentrep':
            case 'rosap':
            case 'gregf':
            case 'lillianh':
            case 'robbier':
            case 'abbyw':
            case 'taylorw':
            case 'abbeyv':
            case 'anniek':
            case 'brendanp':
            case 'carleel':
            case 'gabbyg':
            case 'kimberlyg':
            case 'brooklynnp':
            case 'alexl':
            case 'daphner':
            case 'ellaw':
            case 'caseyt':
            case 'joew':
            case 'isabeld':
            case 'marcelaa':
            case 'haileyp':
            case 'andresj':
            case 'kylaw':
            case 'jadac':
            case 'dyllonj':
            case 'carlyw':
            case 'sawyert':
            case 'evanw':
            case 'kennedyp':
            case 'bellam':
            case 'nanm':
            case 'hannahw':
            case 'julias':
            case 'samh':
            case 'haleym':
            case 'emmack':
            case 'brianj':
            case 'megand':
            case 'kierand':
            case 'tylerb':
            case 'patrickm':
            case 'jaanas':
            case 'susannahh':
            case 'madelynw':
            case 'haleya':
            case 'gracec':
            case 'alliem':
            case 'austins':
            case 'mitchelln':
            case 'richardy':
            case 'emmas':
            case 'averym':
            case 'isabellah':
            case 'harrisond':
            case 'elleno':
            case 'jacobb':
            case 'carlya':
            case 'carlolinek':
            case 'isaiaho':
            case 'margaretd':
            case 'allier':
            case 'madiganw':
            case 'hannahs':
            case 'evelyns':
            case 'annab':
            case 'lizs':
            case 'emilyd':
            case 'paulinem':
            case 'gibs':
            case 'danielt':
            case 'ashleye':
            case 'willh':
            case 'alexisa':
            case 'lindseye':
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

    public function __get($name)
    {
        return $this->config[$name] ?? null;
    }
}
