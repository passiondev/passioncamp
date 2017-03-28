@extends('layouts.roominglist')

@section('content')
    <div class="row">
        <div class="col-9">
            <h1 class="">Rooms</h1>
            <div class="roominglist scroll">
                <div class="alert alert-info">
                    <h3>Important Info About Creating Your Rooming List</h3>
                    <p>All hotel rooms sleep 4 people and account for 2 people in each of the following bed types: king, queen, double, and sleeper sofa. For example, a room with a king bed and sleeper sofa will sleep 4 people.</p>
                    <p>If you place a 5th person in a room, you will need to bring an air mattress. <i>Roll-aways/cots may be requested but <strong>CANNOT</strong> be guaranteed.</i></p>
                </div>
                {{-- <div class="ui fluid icon input">
                    <i class="search icon"></i>
                    <input type="search" placeholder="Search..." data-filter="#rooms" data-filter-item=".room" class="js-filter">
                </div> --}}

                <div id="rooms" class="row">
                    @foreach ($rooms as $room)
                        <div class="col-xl-4 col-6">
                            @include ('roominglist.partials.room')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-3">
            <h1>Unassigned</h1>
            <div class="tickets scroll">
                <div class="ui fluid icon input">
                    <i class="search icon"></i>
                    <input type="search" placeholder="Search..." data-filter="#unassigned" data-filter-item=".ticket" class="js-filter">
                </div>

                <div id="unassigned" class="list-group js-droppable" data-id="0">
                    @foreach($unassigned as $ticket)
                        <div class="list-group-item">
                            @include ('roominglist.partials.ticket')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
@section ('foot')
    <script>
        $(function () {
          new App.Assignments()
        })
    </script>
@stop
