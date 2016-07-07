@extends('layouts.semantic')

@section('content')
    <div class="grid">
        <div class="eleven wide column">
            <h1 style="margin-left:20px">Rooms</h1>
            <div class="roominglist scroll">
                <div class="ui info message" style="margin-bottom:2rem">
                    <h3>Important Info About Creating Your Rooming List</h3>
                    <p>All hotel rooms sleep 4 people and account for 2 people in each of the following bed types: king, queen, double, and sleeper sofa. For example, a room with a king bed and sleeper sofa will sleep 4 people.</p>
                    <p>If you place a 5th person in a room, you will need to bring an air mattress. <i>Roll-aways/cots may be requested but <strong>CANNOT</strong> be guaranteed.</i></p>
                </div>
                <div class="ui fluid icon input">
                    <i class="search icon"></i>
                    <input type="search" placeholder="Search..." data-filter="#rooms" data-filter-item=".room" class="js-filter">
                </div>

                <div id="rooms" class="ui two columns grid" style="margin-top:1rem">
                    @each ('roominglist.partials.room', $rooms, 'room')
                </div>
            </div>
        </div>
        <div class="five wide column">
            <h1 style="margin-left:20px">Unassigned</h1>
            <div class="tickets scroll">
                <div class="ui fluid icon input">
                    <i class="search icon"></i>
                    <input type="search" placeholder="Search..." data-filter="#unassigned" data-filter-item=".ticket" class="js-filter">
                </div>

                <div id="unassigned" class="ui segments js-droppable" data-id="0">
                    @each ('roominglist.partials.ticket', $unassigned, 'ticket')
                    <div class="empty">No Tickets</div>
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
