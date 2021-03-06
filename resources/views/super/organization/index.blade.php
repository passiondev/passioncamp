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
                    <th class="pb-0 text-center border-left">@icon('key')</th>
                    <th class="pb-0 text-center border-left">@icon('checkmark', 'text-success')</th>
                </tr>
                <tr class="table-sm" style="font-size:90%;">
                    <th class="text-info pt-0 text-right thead-default"></th>
                    <th class="text-info pt-0 text-center">${{ number_format($organizations->sum('cached_balance') / 100) }}</th>
                    <th class="text-info pt-0 text-center border-left border-right">{{ number_format($organizations->sum('cached_tickets_sum')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('cached_active_attendees_count')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('cached_assigned_to_room_count')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('cached_completed_waivers_count')) }}</th>
                    <th class="text-info pt-0 text-center border-left">{{ number_format($organizations->sum('cached_rooms_count')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->sum('cached_key_received_rooms_count')) }}</th>
                    <th class="text-info pt-0 text-center">{{ number_format($organizations->where('is_checked_in', true)->count()) }}</th>
                </tr>
                <tr class="text-info table-sm">
                    <td colspan="7"></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    @cache('super.organization._index_row', ['organization' => $organization], 15, $organization->cacheKey())
                @endforeach
            </tbody>
        </table>
    </div>
@stop
