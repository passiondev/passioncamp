@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Update Church</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.update', $organization], 'method' => 'PUT']) }}

            <h3>Church</h3>
            <div class="form-group">
                {{ Form::label('church[name]', 'Name', ['class' => 'control-label']) }}
                {{ Form::text('church[name]', $organization->church->name, ['id' => 'church__name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('church[street]', 'Street', ['class' => 'control-label']) }}
                {{ Form::text('church[street]', $organization->church->street, ['id' => 'church__street', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('church[city]', 'City', ['class' => 'control-label']) }}
                {{ Form::text('church[city]', $organization->church->city, ['id' => 'church__city', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('church[state]', 'State', ['class' => 'control-label']) }}
                {{ Form::text('church[state]', $organization->church->state, ['id' => 'church__state', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('church[zip]', 'Zip Code', ['class' => 'control-label']) }}
                {{ Form::text('church[zip]', $organization->church->zip, ['id' => 'church__zip', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('church[website]', 'Website', ['class' => 'control-label']) }}
                {{ Form::text('church[website]', $organization->church->website, ['id' => 'church__website', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('church[pastor_name]', 'Pastor Name', ['class' => 'control-label']) }}
                {{ Form::text('church[pastor_name]', $organization->church->pastor_name, ['id' => 'church__pastor_name', 'class' => 'form-control']) }}
            </div>

            <h3>Student Pastor</h3>
            <div class="form-group">
                {{ Form::label('student_pastor[first_name]', 'Name', ['class' => 'control-label']) }}
                {{ Form::text('student_pastor[first_name]', $organization->studentPastor->first_name, ['id' => 'student_pastor__first_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('student_pastor[last_name]', 'Name', ['class' => 'control-label']) }}
                {{ Form::text('student_pastor[last_name]', $organization->studentPastor->last_name, ['id' => 'last_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('student_pastor[email]', 'Name', ['class' => 'control-label']) }}
                {{ Form::email('student_pastor[email]', $organization->studentPastor->email, ['id' => 'student_pastor__email', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('student_pastor[phone]', 'Name', ['class' => 'control-label']) }}
                {{ Form::text('student_pastor[phone]', $organization->studentPastor->phone, ['id' => 'student_pastor__phone', 'class' => 'form-control']) }}
            </div>

            <h3>Contact</h3>
            <div class="form-group">
                {{ Form::label('contact[first_name]', 'First Name', ['class' => 'control-label']) }}
                {{ Form::text('contact[first_name]', $organization->contact->first_name, ['id' => 'contact__first_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('contact[last_name]', 'Last Name', ['class' => 'control-label']) }}
                {{ Form::text('contact[last_name]', $organization->contact->last_name, ['id' => 'contact__last_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('contact[email]', 'Email Address', ['class' => 'control-label']) }}
                {{ Form::email('contact[email]', $organization->contact->email, ['id' => 'contact__email', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('contact[phone]', 'Phone Number', ['class' => 'control-label']) }}
                {{ Form::text('contact[phone]', $organization->contact->phone, ['id' => 'contact__phone', 'class' => 'form-control']) }}
            </div>

            <div class="form-group form-actions">
                <button class="btn btn-primary">Update Church</button>
            </div>
        {{ Form::close() }}
    </div>
@stop
