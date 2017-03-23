<span class="color-{{ $ticket->agegroup }}" style="white-space: nowrap">
    {{ ucwords($ticket->agegroup) }}
    @if ($ticket->person->grade)
        <span class="badge badge-{{ $ticket->agegroup }}">
            @ordinal($ticket->person->grade)
        </span>
    @endif
</span>
