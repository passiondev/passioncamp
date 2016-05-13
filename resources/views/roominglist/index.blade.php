@extends('layouts.semantic')

@section('content')
    <div class="grid">
        <div class="eleven wide column">
            <h1>Rooms</h1>
            <div class="roominglist scroll">
                <div class="ui two columns grid">
                    @each ('roominglist.partials.room', $rooms, 'room')
                </div>
            </div>
        </div>
        <div class="five wide column">
            <h1>Unassigned</h1>
            <div class="tickets scroll">
                <div id="unassigned" class="ui segments js-droppable" data-id="0">
                    @each ('roominglist.partials.ticket', $unassigned, 'ticket')
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
