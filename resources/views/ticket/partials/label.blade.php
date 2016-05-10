<div class="ui label {{ $ticket->agegroup=='student' ? 'purple' : '' }} {{ $ticket->agegroup=='leader' ? 'teal' : '' }}">
    {{ ucwords($ticket->agegroup) }}
    @if (strlen($ticket->person->grade) and $ticket->person->grade > 0)
        - @ordinal($ticket->person->grade)
    @endif
</div>
