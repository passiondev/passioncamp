@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="page-header">
            <h1>Hotels</h1>
        </header>
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-center">Capacity</th>
                    <th class="text-center">Booked</th>
                    <th class="text-center">Remaining</th>
                    <th class="text-center">Churches</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotels as $hotel)
                    <tr>
                        <td><a href="{{ action('HotelController@show', $hotel) }}">{{ $hotel->name }}</a></td>
                        <td class="text-center">{{ number_format($hotel->capacity) }}</td>
                        <td class="text-center">{{ $hotel->registered_sum }}</td>
                        <td class="text-center">{{ $hotel->remaining_count }}</td>
                        <td class="text-center">{{ $hotel->organizations_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
