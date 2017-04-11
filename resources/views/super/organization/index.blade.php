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
                    <th class="text-center border-left">Purchased Tickets</th>
                    <th class="text-center border-right">Rooms</th>
                    <th class="text-center">Registered</th>
                    <th class="text-center">Assigned to Room</th>
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
                            {{ money_format('%.0n', $organization->balance / 100) }}
                        </td>
                        <td class="text-center border-left">{{ $organization->num_tickets }}</td>
                        <td class="text-center border-right {{ $organization->hotel_items_count != $organization->rooms->count() ? 'table-danger' : '' }}">
                            {{ $organization->rooms->count() }}
                        </td>
                        <td class="text-center {{ $organization->attendees->active()->count() > $organization->num_tickets ? 'table-danger' : '' }}">
                            {{ $organization->attendees->active()->count() }}
                        </td>
                        <td class="text-center {{ $organization->assigned_to_room_count > 0 && $organization->assigned_to_room_count == $organization->attendees->active()->count() ? 'table-success' : '' }}">
                            {{ number_format($organization->assigned_to_room_count) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $organizations->links() }}
    </div>
@stop
