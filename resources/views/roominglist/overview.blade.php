@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <table class="ui striped table">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Hotel</th>
                    <th>Room</th>
                    <th style="text-align:center">Capacity</th>
                    <th style="text-align:center">Assigned</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $room->organization->church->name }} - {{ $room->organization->church->location }}</td>
                        <td>{{ $room->hotel->name }}</td>
                        <td>{{ $room->name }}</td>
                        <td style="text-align:center">{{ $room->capacity }}</td>
                        <td style="text-align:center">{{ $room->tickets->count() }}</td>
                        <td><a href="{{ route('roominglist.edit', $room) }}">edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
