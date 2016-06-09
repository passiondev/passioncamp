<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Organization;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('items')->get();
        
        return view('admin.hotel.index')->withHotels($hotels);
    }

    public function show(Hotel $hotel)
    {
        $hotel->load('organizations.church');
        return view('admin.hotel.show')->withHotel($hotel);
    }
}
