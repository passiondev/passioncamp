@extends('layouts.roominglist')

@section ('head')
<script>
    window.store = {!! json_encode([
        'unassigned' => $unassigned,
        'dragging' => false
    ]) !!};
</script>
@endsection

@section('content')
    <div class="row d-flex align-items-stretch h-100">
        <div class="rooms overflowing col-9 d-flex flex-column h-100">
            <h1>Rooms</h1>
            <div id="rooms-scroll" style="overflow-y: scroll; overflow-x: hidden">
                <div class="alert alert-info">
                    <h3>Important Info About Creating Your Rooming List</h3>
                    <p>All hotel rooms sleep 4 people and account for 2 people in each of the following bed types: king, queen, double, and sleeper sofa. For example, a room with a king bed and sleeper sofa will sleep 4 people.</p>
                    <p>If you place a 5th person in a room, you will need to bring an air mattress. <i>Roll-aways/cots may be requested but <strong>CANNOT</strong> be guaranteed.</i></p>
                </div>
                <div class="row">
                    @foreach ($rooms as $room)
                        <div class="col-xl-4 col-6 mb-3">
                            <roominglist-room :room="{{ json_encode($room) }}" url="{{ action('RoomAssignmentController@update', $room) }}">
                                @if (Auth::user()->isSuperAdmin())
                                    <template slot="organization">
                                        <small class="text-muted">{{ $room->organization->church->name }}</small><br>
                                    </template>
                                @endif
                                <div slot="actions">
                                    <a href="{{ action('RoomController@edit', $room) }}" class="btn btn-outline-secondary btn-sm">edit</a>
                                    @if (Auth::user()->isSuperAdmin())
                                        {{-- <span><a href="{{ route('roominglist.label', $room) }}" {!! session('printer') == 'PDF' ? 'target="_blank"' : '' !!} {!! session('printer') && session('printer') != 'PDF' ? 'data-test="test" v-on:click.prevent="ajax"' : '' !!}>print</a></span> --}}
                                    @endif
                                </div>
                            </roominglist-room>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <roominglist-unassigned></roominglist-unassigned>
    </div>
@stop
