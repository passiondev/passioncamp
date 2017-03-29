@extends('layouts.roominglist')

@section ('head')
<script>
    window.store = {!! json_encode([
        'unassigned' => $unassigned
    ]) !!};
</script>
@endsection

@section('content')
    <div class="row d-flex align-items-stretch h-100">
        <div class="rooms overflowing col-9 d-flex flex-column h-100">
            <h1>Rooms</h1>
            <div style="overflow-y: scroll">
                <div class="alert alert-info">
                    <h3>Important Info About Creating Your Rooming List</h3>
                    <p>All hotel rooms sleep 4 people and account for 2 people in each of the following bed types: king, queen, double, and sleeper sofa. For example, a room with a king bed and sleeper sofa will sleep 4 people.</p>
                    <p>If you place a 5th person in a room, you will need to bring an air mattress. <i>Roll-aways/cots may be requested but <strong>CANNOT</strong> be guaranteed.</i></p>
                </div>
                <div class="row">
                    @foreach ($rooms as $room)
                        <div class="col-xl-4 col-6 mb-3">
                            @include ('roominglist.partials.room')
                        </div>
                        <div class="col-xl-4 col-6 mb-3">
                            @include ('roominglist.partials.room')
                        </div>
                        <div class="col-xl-4 col-6 mb-3">
                            @include ('roominglist.partials.room')
                        </div>
                        <div class="col-xl-4 col-6 mb-3">
                            @include ('roominglist.partials.room')
                        </div>
                        <div class="col-xl-4 col-6 mb-3">
                            @include ('roominglist.partials.room')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tickets overflowing col-3 d-flex flex-column h-100">
            <h1>Tickets</h1>
            <div style="overflow-y: scroll;padding-bottom: 20px">
                <roominglist-unassigned></roominglist-unassigned>
                {{-- <div id="unassigned" class="list-group js-droppable" data-id="0">
                    @foreach($unassigned as $ticket)
                        <div class="list-group-item">
                            @include ('roominglist.partials.ticket')
                        </div>
                    @endforeach
                </div> --}}
            </div>
        </div>
    </div>
@stop
