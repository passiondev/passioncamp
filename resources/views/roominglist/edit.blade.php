@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <div class="page-header__title">
                <h1>Edit {{ $room->name }}</h1>
                <h3>{{ $room->organization->church->name }}</h3>
            </div>
        </header>

        {{ Form::model($room, ['route' => ['roominglist.update', $room], 'method' => 'PATCH', 'class' => 'ui form']) }}
            @if (Auth::user()->isSuperAdmin())
                <div class="field">
                    <label for="name">Name</label>
                    {{ Form::text('name', null, ['id' => 'name']) }}
                </div>
                <div class="field">
                    <label for="hotel">Hotel</label>
                    {{ Form::select('hotel_id', $hotelOptions, null, ['id' => 'hotel', 'class' => 'ui dropdown']) }}
                </div>
                <div class="field">
                    <label for="roomnumber">Room #</label>
                    {{ Form::text('roomnumber', null, ['id' => 'roomnumber']) }}
                </div>
                <div class="field">
                    <label for="confirmation_number">Confirmation #</label>
                    {{ Form::text('confirmation_number', null, ['id' => 'confirmation_number']) }}
                </div>
            @endif
            <div class="field">
                {{ Form::label('capacity', 'Capacity') }}
                {{ Form::select('capacity', array(4 => 4,5), null, ['id' => 'capacity', 'class' => 'ui dropdown']) }}
            </div>
            <div class="field">
                {{ Form::label('description', 'Description') }}
                {{ Form::text('description', null, ['id' => 'description', 'placeholder' => 'ie 6th Grade Girls']) }}
            </div>
            <div class="field">
                {{ Form::label('notes', 'Notes') }}
                {{ Form::text('notes', null, ['id' => 'notes', 'placeholder' => 'ie King Bed OK']) }}
            </div>
            <button class="ui primary button">Update</button>
        {{ Form::close() }}

        @if (Auth::user()->isSuperAdmin())
        <br><br><br>
            <hr>
            <br><br><br>
            {{ Form::open(['route' => ['roominglist.destroy', $room], 'method' => 'DELETE', 'class' => 'ui form', 'onsubmit' => 'return confirm("Are you sure?")']) }}
                <button type="submit" class="ui tiny red button">Remove Room & Ticket Assignments</button>
            {{ Form::close() }}
        @endif
    </div>
@stop
