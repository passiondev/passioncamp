<div class="room card js-droppable {{ $room->is_at_capacity ? 'full' : '' }}" data-id="{{ $room->id }}">
    <header class="card-header">
        <div class="d-flex justify-content-between align-items-baseline">
            <h4>
                @if (Auth::user()->isSuperAdmin())
                    <small class="text-muted">{{ $room->organization->church->name }}</small><br>
                @endif
                {{ $room->name }}
            </h4>

            <div>
                <a href="{{ action('RoomController@edit', $room) }}" class="btn btn-outline-secondary btn-sm">edit</a>
                @if (Auth::user()->isSuperAdmin())
                    {{-- <span><a href="{{ route('roominglist.label', $room) }}" {!! session('printer') == 'PDF' ? 'target="_blank"' : '' !!} {!! session('printer') && session('printer') != 'PDF' ? 'data-test="test" v-on:click.prevent="ajax"' : '' !!}>print</a></span> --}}
                @endif
            </div>
        </div>
    </header>
    <div class="card-block">
        <div class="statistics justify-content-center mb-3">
            <div class="blue statistic">
                <div class="value">{{ $room->assigned }}</div>
                <div class="label">Assigned</div>
            </div>
            <div class="green statistic">
                <div class="value">{{ $room->capacity }}</div>
                <div class="label">Capacity</div>
            </div>
        </div>

        <div class="tickets">
            <div class="list-group">
                @foreach($room->tickets->assigendSort() as $ticket)
                    <div class="list-group-item">
                        @include ('roominglist.partials.ticket')
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-footer text-muted bg-white">
        @if (strlen($room->description) || strlen($room->notes))
            <h6 class="mb-0">{{ $room->description }}</h6>
            <p class="card-text">{{ $room->notes }}</p>
        @endif
    </div>
</div>
