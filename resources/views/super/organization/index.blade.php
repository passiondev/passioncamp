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
            <thead class="thead-default">
                <tr>
                    <th class="pb-0 "></th>
                    <th class="pb-0 text-center">Balance</th>
                    <th class="pb-0 text-center border-left border-right">Tickets</th>
                    <th class="pb-0 text-center">Attendees</th>
                    <th class="pb-0 text-center">Room Assigned</th>
                    <th class="pb-0 text-center">Waiver Complete</th>
                    <th class="pb-0 text-center border-left">Rooms</th>
                </tr>
                <tr class="table-sm" style="font-size:90%;">
                    <th class="text-info pt-0 text-right thead-default"></th>
                    <th class="text-info pt-0 text-center">${{ number_format($organizations->sum('balance') / 100) }}</th>
                    <th class="text-info pt-0 text-center border-left border-right">{{ number_format($organizations->sum('tickets_sum')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('active_attendees_count')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('assigned_to_room_count')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('completed_waivers_count')) }}</th>
                    <th class="text-info pt-0 text-center border-left">{{ number_format($organizations->sum('rooms_count')) }}</th>
                </tr>
                <tr class="text-info table-sm">
                    <td colspan="7"></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <td class="">
                                <a href="{{ action('OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a>
                                <small>{{ $organization->church->location }}</small>
                        </td>
                        <td class="text-center {{ $organization->balance > 0 ? 'table-warning' : '' }}">
                            {{ $organization->balance == 0 ? '--' : money_format('%.0n', $organization->balance / 100) }}
                        </td>
                        <td class="text-center border-left border-right">
                            {{ $organization->tickets_sum }}
                        </td>
                        <td class="text-center {{ $organization->active_attendees_count > $organization->tickets_sum ? 'table-danger' : '' }}">
                            {{ $organization->active_attendees_count }}
                        </td>
                        <td class="text-center {{ $organization->assigned_to_room_count > 0 && $organization->assigned_to_room_count == $organization->active_attendees_count ? 'table-success' : '' }}">
                            {{ $organization->rooms_count ? number_format($organization->assigned_to_room_count) : '--' }}
                        </td>
                        <td class="text-center {{ $organization->completed_waivers_count > 0 && $organization->completed_waivers_count == $organization->active_attendees_count ? 'table-success' : '' }}">
                            {{ number_format($organization->completed_waivers_count) }}
                        </td>
                        <td class="text-center border-left {{ $organization->hotels_sum != $organization->rooms_count ? 'table-danger' : '' }}">
                            {{ $organization->rooms_count ?: '--' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
