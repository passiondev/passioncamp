<?php

namespace App\Http\Controllers;

use App\Hotel;
use App\Organization;
use Illuminate\Http\Request;


class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('super');
    }

    public function index()
    {
        $hotels = Hotel::all();
        
        return view('admin.hotel.index')->withHotels($hotels);
    }

    public function show(Hotel $hotel)
    {
        return view('admin.hotel.show')->withHotel($hotel);
    }
}
