<span class="label label--{{ $ticket->agegroup }}">
    {{ ucwords($ticket->agegroup) }}
    @if (strlen($ticket->person->grade) and $ticket->person->grade > 0)
        - @ordinal($ticket->person->grade)
    @endif
</span>
