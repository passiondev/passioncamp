@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Update Church</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.update', $organization], 'method' => 'PUT', 'class' => 'ui form']) }}

            <h3>Church</h3>
            <div class="field">
                {{ Form::label('church[name]', 'Name') }}
                {{ Form::text('church[name]', $organization->church->name, ['id' => 'church__name']) }}
            </div>
            <div class="field">
                {{ Form::label('church[street]', 'Street') }}
                {{ Form::text('church[street]', $organization->church->street, ['id' => 'church__street']) }}
            </div>
            <div class="field">
                {{ Form::label('church[city]', 'City') }}
                {{ Form::text('church[city]', $organization->church->city, ['id' => 'church__city']) }}
            </div>
            <div class="field">
                {{ Form::label('church[state]', 'State') }}
                {{ Form::text('church[state]', $organization->church->state, ['id' => 'church__state']) }}
            </div>
            <div class="field">
                {{ Form::label('church[zip]', 'Zip Code') }}
                {{ Form::text('church[zip]', $organization->church->zip, ['id' => 'church__zip']) }}
            </div>
            <div class="field">
                {{ Form::label('church[website]', 'Website') }}
                {{ Form::text('church[website]', $organization->church->website, ['id' => 'church__website']) }}
            </div>
            <div class="field">
                {{ Form::label('church[pastor_name]', 'Pastor Name') }}
                {{ Form::text('church[pastor_name]', $organization->church->pastor_name, ['id' => 'church__pastor_name']) }}
            </div>

            <h3>Student Pastor</h3>
            <div class="field">
                {{ Form::label('student_pastor[first_name]', 'First Name') }}
                {{ Form::text('student_pastor[first_name]', $organization->studentPastor->first_name, ['id' => 'student_pastor__first_name']) }}
            </div>
            <div class="field">
                {{ Form::label('student_pastor[last_name]', 'Last Name') }}
                {{ Form::text('student_pastor[last_name]', $organization->studentPastor->last_name, ['id' => 'last_name']) }}
            </div>
            <div class="field">
                {{ Form::label('student_pastor[email]', 'Email Address') }}
                {{ Form::email('student_pastor[email]', $organization->studentPastor->email, ['id' => 'student_pastor__email']) }}
            </div>
            <div class="field">
                {{ Form::label('student_pastor[phone]', 'Phone Number') }}
                {{ Form::text('student_pastor[phone]', $organization->studentPastor->phone, ['id' => 'student_pastor__phone']) }}
            </div>

            <h3>Contact</h3>
            <div class="field">
                {{ Form::label('contact[first_name]', 'First Name') }}
                {{ Form::text('contact[first_name]', $organization->contact->first_name, ['id' => 'contact__first_name']) }}
            </div>
            <div class="field">
                {{ Form::label('contact[last_name]', 'Last Name') }}
                {{ Form::text('contact[last_name]', $organization->contact->last_name, ['id' => 'contact__last_name']) }}
            </div>
            <div class="field">
                {{ Form::label('contact[email]', 'Email Address') }}
                {{ Form::email('contact[email]', $organization->contact->email, ['id' => 'contact__email']) }}
            </div>
            <div class="field">
                {{ Form::label('contact[phone]', 'Phone Number') }}
                {{ Form::text('contact[phone]', $organization->contact->phone, ['id' => 'contact__phone']) }}
            </div>

            <button class="ui primary button">Update Church</button>
        {{ Form::close() }}
    </div>
@stop
