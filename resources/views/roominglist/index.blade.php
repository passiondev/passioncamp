@extends('layouts.semantic')

@section('content')
    <div class="grid">
        <div class="eleven wide column">
            <h1>Rooms</h1>
            <div class="roominglist scroll">
                <div class="ui grid">
                    @each ('roominglist.partials.room', $rooms, 'room')
                </div>
            </div>
        </div>
        <div class="five wide column">
            <h1>Unassigned</h1>
            <div class="tickets ui segments scroll js-droppable js-sortable">
                @each ('roominglist.partials.ticket', $unassigned, 'ticket')
            </div>
        </div>
    </div>
@stop
