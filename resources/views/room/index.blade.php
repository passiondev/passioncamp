@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Hotel</th>
                    <th>Name</th>
                    <th>People</th>
                    <th class="text-center">Capacity</th>
                    <th class="text-center">Assigned</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <td style="line-height: 1">
                            <strong><a href="{{ action('OrganizationController@show', $room->organization) }}">{{ $room->organization->church->name }}</a></strong><br>
                            <small class="text-muted">{{ $room->organization->church->location }}</small>
                        </td>
                        <td>{{ $room->hotelName }}</td>
                        <td>{{ $room->name }}</td>
                        <td>
                            <ul class="list-unstyled mb-0" style="font-size:85%; line-height: 1.2">
                                @foreach ($room->tickets as $ticket)
                                    <li>{{ $ticket->person->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center">{{ $room->capacity }}</td>
                        <td class="text-center">{{ $room->tickets->count() }}</td>
                        <td>
                            <ul class="list-unstyled mb-0" style="font-size:85%">
                                <li>
                                    <ajax href="{{ action('RoomController@keyReceived', $room) }}" method="POST" :is-success="{{ json_encode($room->is_key_received) }}" v-cloak>
                                        receive key
                                        <span slot="success">
                                            @icon('checkmark', 'text-success') key
                                        </span>
                                    </ajax>
                                </li>
                                <li>
                                    <ajax href="{{ action('RoomController@checkin', $room) }}" method="POST" :is-success="{{ json_encode($room->is_checked_in) }}" v-cloak>
                                        check in
                                        <span slot="success">
                                            @icon('checkmark', 'text-success') checked in
                                        </span>
                                    </ajax>
                                </li>
                                <li>
                                    <a href="{{ action('RoomController@edit', $room) }}">edit</a>
                                </li>
                                <li>print</li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
