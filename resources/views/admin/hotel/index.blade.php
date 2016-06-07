@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Hotels</h1>
        </header>
        <table class="ui very basic unstackable fixed table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="text-align:center">Capacity</th>
                    <th style="text-align:center">Booked</th>
                    <th style="text-align:center">Remaining</th>
                    <th style="text-align:center">Churches</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotels as $hotel)
                    <tr>
                        <td><a href="{{ route('hotel.show', $hotel) }}">{{ $hotel->name }}</a></td>
                        <td style="text-align:center">{{ $hotel->capacity }}</td>
                        <td style="text-align:center">{{ $hotel->registered_count }}</td>
                        <td style="text-align:center">{{ $hotel->remaining_count }}</td>
                        <td style="text-align:center">{{ $hotel->organizations->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
