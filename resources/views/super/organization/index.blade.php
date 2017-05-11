@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-flex justify-content-between">
            <h1>Churches</h1>
            <p>
                <a class="btn btn-secondary" href="{{ action('OrganizationController@create') }}">Add Church</a>
            </p>
        </header>

        <table class="table table-responsive table-bordered table-align-middle" style="table-layout: fixed;width:100%;">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">Balance</th>
                    <th class="text-center border-left border-right">Purchased Tickets</th>
                    <th class="text-center">Registered</th>
                    <th class="text-center">Assigned to Room</th>
                    <th class="text-center">Completed Waivers</th>
                    <th class="text-center border-left">Rooms</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <th class="table-active">
                                <a href="{{ action('OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a>
                                <small>{{ $organization->church->location }}</small>
                        </th>
                        <td class="text-center {{ $organization->balance > 0 ? 'table-warning' : '' }}">
                            {{ $organization->balance == 0 ? '--' : money_format('%.0n', $organization->balance / 100) }}
                        </td>
                        <td class="text-center border-left border-right">{{ $organization->tickets_sum }}</td>
                        <td class="text-center {{ $organization->active_attendees_count > $organization->tickets_sum ? 'table-danger' : '' }}">
                            {{ $organization->active_attendees_count }}
                        </td>
                        <td class="text-center {{ $organization->assigned_to_room_count > 0 && $organization->assigned_to_room_count == $organization->active_attendees_count ? 'table-success' : '' }}">
                            {{ number_format($organization->assigned_to_room_count) }}
                        </td>
                        <td class="text-center {{ $organization->completed_waivers_count > 0 && $organization->completed_waivers_count == $organization->active_attendees_count ? 'table-success' : '' }}">
                            {{ number_format($organization->completed_waivers_count) }}
                        </td>
                        <td class="text-center border-left {{ $organization->hotels_sum != $organization->rooms_count ? 'table-danger' : '' }}">
                            {{ $organization->rooms_count }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
