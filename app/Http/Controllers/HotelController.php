<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Scopes\OrganizationCountsScope;
use App\OrderItem;
use App\Organization;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function index()
    {
        $hotels = Hotel::withPurchasedSum()->withDistinctOrganizationsCount()->get();

        return view('admin.hotel.index')->withHotels($hotels);
    }

    public function show(Hotel $hotel)
    {
        $hotel->load([
            'organizations' => function ($q) {
                $q->withoutGlobalScopes();
            },
            'organizations.church',
        ]);

        return view('admin.hotel.show')->withHotel($hotel);
    }
}
