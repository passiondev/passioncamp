<div class="ticket ui segment js-draggable" data-id="{{ $ticket->id }}" data-room-id="{{ $ticket->room ? $ticket->room->id : 0 }}">
    {{ $ticket->name }}
    <div class="meta">
        @include('ticket/partials/label')
        @if ($ticket->person->gender == 'M')
            <span class="ui blue mini circular label">&nbsp;</span>
        @elseif ($ticket->person->gender == 'F')
            <span class="ui pink mini circular label">&nbsp;</span>
        @endif
    </div>
</div>