<tr>
    <td class="">
            <a href="{{ action('OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a>
        <small>{{ $organization->contact->name }} &middot;
        {{ $organization->church->location }}</small>
    </td>
    <td class="text-center {{ $organization->balance > 0 ? 'table-warning' : '' }}">
        {{ $organization->balance == 0 ? '--' : \Money\Money::USD($organization->balance / 100) }}
    </td>
    <td class="text-center border-left border-right">
        {{ $organization->tickets_sum }}
    </td>
    <td class="text-center {{ $organization->active_attendees_count > $organization->tickets_sum ? 'table-danger' : '' }}">
        {{ $organization->active_attendees_count }}
    </td>
    <td class="text-center {{ $organization->assigned_to_room_count > 0 && $organization->assigned_to_room_count == $organization->active_attendees_count ? 'table-success' : '' }}">
        {{ $organization->hotels_sum ? number_format($organization->assigned_to_room_count) : '--' }}
    </td>
    <td class="text-center {{ $organization->completed_waivers_count > 0 && $organization->completed_waivers_count == $organization->active_attendees_count ? 'table-success' : '' }}">
        {{ number_format($organization->completed_waivers_count) }}
    </td>
    <td class="text-center border-left {{ $organization->hotels_sum != $organization->rooms_count ? 'table-danger' : '' }}">
        {{ $organization->hotels_sum ? $organization->rooms_count : '--' }}
    </td>
    <td class="text-center">
        @if($organization->key_received_rooms_count)
            {{ $organization->key_received_rooms_count }}
        @endif
    </td>
    <td class="text-center">
        @if ($organization->is_checked_in)
            @icon('checkmark', 'text-success')
        @endif
    </td>
</tr>
