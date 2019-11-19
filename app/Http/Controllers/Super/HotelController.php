<?php

namespace App\Http\Controllers\Super;

use App\Hotel;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware(Authenticate::class);
        $this->authorizeResource(Hotel::class);
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
