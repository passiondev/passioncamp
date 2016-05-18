@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <div class="page-header__title">
                <h1>Edit {{ $room->name }}</h1>
            </div>
        </header>

        {{ Form::model($room, ['route' => ['roominglist.update', $room], 'method' => 'PATCH', 'class' => 'ui form']) }}
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
    </div>
@stop
