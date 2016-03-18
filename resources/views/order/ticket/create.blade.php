@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Attendee</h1>
            <h2>Registration #{{ $order->id }}</h2>
        </header>

        {{ Form::open(['route' => ['order.ticket.store', $order]]) }}

            <div class="form-group">
                {{ Form::label('agegroup', 'Type', ['class' => 'control-label']) }}
                {{ Form::select('agegroup', ['student' => 'Student', 'leader' => 'Leader'], null, ['id' => 'agegroup', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
                {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('last_name', 'Last Name', ['class' => 'control-label']) }}
                {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('gender', 'Gender', ['class' => 'control-label']) }}
                <div class="form-controls form-controls--radio">
                    <label class="radio-inline">
                        <input type="radio" value="M" name="gender" id="gender--M"> Male
                    </label>
                    <label class="radio-inline">
                        <input type="radio" value="F" name="gender" id="gender--F"> Female
                    </label>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('birthdate', 'Birthdate', ['class' => 'control-label']) }}
                <input class="form-control js-form-input-date" placeholder="mm/dd/yyyy" type="text" id="birthdate" name="birthdate">
            </div>
            <div class="form-group">
                {{ Form::label('grade', 'Grade', ['class' => 'control-label']) }}
                {{ Form::select('grade', $gradeOptions, null, ['id' => 'grade', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('price', 'Price', ['class' => 'control-label']) }}
                {{ Form::text('price', null, ['id' => 'price', 'class' => 'form-control']) }}
            </div>

            <div class="form-group form-actions">
                <button class="btn btn-primary">Create Ticket</button>
            </div>

        {{ Form::close() }}
    </div>
@stop
