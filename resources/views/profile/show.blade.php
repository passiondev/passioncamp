@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="ui dividing header">
            <h1>Update Profile</h1>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                <form action="{{ action('ProfileController@update') }}" method="POST" novalidate>
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    @include ('errors.validation')

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input class="form-control" type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->person->first_name) }}">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input class="form-control" type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->person->last_name) }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Update Password</label>
                        <input class="form-control" type="password" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                    </div>

                    <button class="btn btn-primary" type="submit">Update</button>

                </form>
            </div>
        </div>
    </div>
@stop
