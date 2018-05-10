<tr>
    <td class="">
        <a href="{{ action('OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a>
        <small>{{ $organization->contact->name }} &middot;
        {{ $organization->church->location }}</small>
    </td>
    <td class="text-center {{ $organization->cached_balance > 0 ? 'table-warning' : '' }}">
        {{ $organization->cached_balance == 0 ? '--' : money_format('%.0n', $organization->cached_balance / 100) }}
    </td>
    <td class="text-center border-left border-right">
        {{ $organization->cached_tickets_sum }}
    </td>
    <td class="text-center {{ $organization->cached_active_attendees_count > $organization->cached_tickets_sum ? 'table-danger' : '' }}">
        {{ $organization->cached_active_attendees_count }}
    </td>
    <td class="text-center {{ $organization->cached_assigned_to_room_count > 0 && $organization->cached_assigned_to_room_count == $organization->cached_active_attendees_count ? 'table-success' : '' }}">
        {{ $organization->cached_hotels_sum ? number_format($organization->cached_assigned_to_room_count) : '--' }}
    </td>
    <td class="text-center {{ $organization->cached_completed_waivers_count > 0 && $organization->cached_completed_waivers_count == $organization->cached_active_attendees_count ? 'table-success' : '' }}">
        {{ number_format($organization->cached_completed_waivers_count) }}
    </td>
    <td class="text-center border-left {{ $organization->cached_hotels_sum != $organization->cached_rooms_count ? 'table-danger' : '' }}">
        {{ $organization->cached_hotels_sum ? $organization->cached_rooms_count : '--' }}
    </td>
    <td class="text-center">
        @if($organization->cached_key_received_rooms_count)
            {{ $organization->cached_key_received_rooms_count }}
        @endif
    </td>
    <td class="text-center">
        @if ($organization->is_checked_in)
            @icon('checkmark', 'text-success')
        @endif
    </td>
</tr>
