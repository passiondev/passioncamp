@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Update Profile</h1>
        </header>

        <div class="row">
            <div class="large-5 columns">
                {{ Form::model($form_data, ['route' => 'profile.update', 'method' => 'PATCH', 'novalidate']) }}
                    @include ('errors.validation')

                    <div class="form-group">
                        {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
                        {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('last_name', 'Last Name', ['class' => 'control-label']) }}
                        {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', 'Email Address', ['class' => 'control-label']) }}
                        {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password', 'Password', ['class' => 'control-label']) }}
                        {{ Form::password('password', null, ['id' => 'password', 'class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password_confirmation', 'Password Confirmation', ['class' => 'control-label']) }}
                        {{ Form::password('password_confirmation', null, ['id' => 'password_confirmation', 'class' => 'form-control']) }}
                    </div>

                    <div class="form-group form-actions">
                        <button type="submit">Update</button>
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
