@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Church</h1>
        </header>

        {{ Form::open(['route' => 'admin.organization.store', 'class' => 'ui form']) }}

            <h3>Church</h3>
            <div class="field">
                {{ Form::label('church[name]', 'Name') }}
                {{ Form::text('church[name]', null, ['id' => 'church__name']) }}
            </div>
            <div class="field">
                {{ Form::label('church[street]', 'Street') }}
                {{ Form::text('church[street]', null, ['id' => 'church__street']) }}
            </div>
            <div class="field">
                {{ Form::label('church[city]', 'City') }}
                {{ Form::text('church[city]', null, ['id' => 'church__city']) }}
            </div>
            <div class="field">
                {{ Form::label('church[state]', 'State') }}
                {{ Form::text('church[state]', null, ['id' => 'church__state']) }}
            </div>
            <div class="field">
                {{ Form::label('church[zip]', 'Zip Code') }}
                {{ Form::text('church[zip]', null, ['id' => 'church__zip']) }}
            </div>
            <div class="field">
                {{ Form::label('church[website]', 'Website') }}
                {{ Form::text('church[website]', null, ['id' => 'church__website']) }}
            </div>
            <div class="field">
                {{ Form::label('church[pastor_name]', 'Pastor Name') }}
                {{ Form::text('church[pastor_name]', null, ['id' => 'church__pastor_name']) }}
            </div>

            <h3>Student Pastor</h3>
            <div class="field">
                {{ Form::label('student_pastor[first_name]', 'First Name') }}
                {{ Form::text('student_pastor[first_name]', null, ['id' => 'student_pastor__first_name']) }}
            </div>
            <div class="field">
                {{ Form::label('student_pastor[last_name]', 'Last Name') }}
                {{ Form::text('student_pastor[last_name]', null, ['id' => 'last_name']) }}
            </div>
            <div class="field">
                {{ Form::label('student_pastor[email]', 'Email Address') }}
                {{ Form::email('student_pastor[email]', null, ['id' => 'student_pastor__email']) }}
            </div>
            <div class="field">
                {{ Form::label('student_pastor[phone]', 'Phone Number') }}
                {{ Form::text('student_pastor[phone]', null, ['id' => 'student_pastor__phone']) }}
            </div>

            <h3>Contact</h3>
            <div class="field">
                {{ Form::label('contact[first_name]', 'First Name') }}
                {{ Form::text('contact[first_name]', null, ['id' => 'contact__first_name']) }}
            </div>
            <div class="field">
                {{ Form::label('contact[last_name]', 'Last Name') }}
                {{ Form::text('contact[last_name]', null, ['id' => 'contact__last_name']) }}
            </div>
            <div class="field">
                {{ Form::label('contact[email]', 'Email Address') }}
                {{ Form::email('contact[email]', null, ['id' => 'contact__email']) }}
            </div>
            <div class="field">
                {{ Form::label('contact[phone]', 'Phone Number') }}
                {{ Form::text('contact[phone]', null, ['id' => 'contact__phone']) }}
            </div>

            <button class="ui primary button">Create Church</button>
        {{ Form::close() }}
    </div>
@stop
