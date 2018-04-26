<?php

namespace App\Http\Controllers;

use App\Hotel;

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

        return view('admin.hotel.index', compact('hotels'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load('organizations.church');

        return view('admin.hotel.show', compact('hotel'));
    }
}
