@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Update Profile</h1>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                {{ Form::model($form_data, ['route' => 'profile.update', 'method' => 'PATCH', 'novalidate', 'class' => 'ui form']) }}
                    @include ('errors.validation')

                    <div class="field">
                        {{ Form::label('first_name', 'First Name') }}
                        {{ Form::text('first_name', null, ['id' => 'first_name']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('last_name', 'Last Name') }}
                        {{ Form::text('last_name', null, ['id' => 'last_name']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('email', 'Email Address') }}
                        {{ Form::email('email', null, ['id' => 'email']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('password', 'Password') }}
                        {{ Form::password('password', null, ['id' => 'password']) }}
                    </div>
                    <div class="field">
                        {{ Form::label('password_confirmation', 'Confirm Password') }}
                        {{ Form::password('password_confirmation', null, ['id' => 'password_confirmation']) }}
                    </div>

                    <button class="ui primary button" type="submit">Update</button>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop
