<div class="column">
    <div class="room ui segment js-droppable" data-id="{{ $room->id }}">
        <header>
            <div class="ui dividing header" style="display:flex;justify-content:space-between;align-items:baseline">
                <h4>{{ $room->name }}</h4>
                <div class="ui sub header" style="text-transform:none">
                    <a href="{{ route('roominglist.edit', $room) }}">edit</a>
                </div>
            </div>
            @if (strlen($room->description) || strlen($room->notes))
                <div>
                    <h5>{{ $room->description }}</h5>
                    <p>{{ $room->notes }}</p>
                </div>
                <div class="ui divider"></div>
            @endif
        </header>
        <div class="stats">
            <div class="ui mini statistics" style="font-size:.675em">
                <div class="blue statistic" style="margin-bottom: 0">
                    <div class="value">{{ $room->assigned }}</div>
                    <div class="label">Assigned</div>
                </div>
                <div class="green statistic" style="margin-bottom: 0">
                    <div class="value">{{ $room->capacity }}</div>
                    <div class="label">Capacity</div>
                </div>
            </div>
        </div>
        <div class="ui divider" style="margin:.5rem 0"></div>
        <div class="tickets">
            <div class="ui segments">
                @each ('roominglist.partials.ticket', $room->tickets->assigendSort(), 'ticket', 'roominglist.partials.noticket')
            </div>
        </div>
    </div>
</div>