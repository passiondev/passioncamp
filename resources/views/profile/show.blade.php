@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        @include('errors.validation')
        <div class="card mb-3">
            <h4 class="card-header">Update Profile</h4>
            <div class="card-block">
                <form action="{{ action('ProfileController@update') }}" method="POST" novalidate>
                    {{ method_field('PATCH') }}
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="first_name" class="col-md-3 col-form-label text-md-right">First Name</label>
                        <div class="col-md-8 col-lg-6">
                            <input class="form-control" type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->person->first_name) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last_name" class="col-md-3 col-form-label text-md-right">Last Name</label>
                        <div class="col-md-8 col-lg-6">
                            <input class="form-control" type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->person->last_name) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                        <div class="col-md-8 col-lg-6">
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col offset-md-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <h4 class="card-header">Connected Accounts</h4>
            <div class="card-block">

            @if ($user->hasSocialAccountFor('google'))
                <div class="input-group">
                    <span class="input-group-addon bg-google">@icon('google')</span>
                    <span class="input-group-btn">
                        <a href="{{ action('SocialAuthController@disconnect', 'google') }}" class="btn btn-outline-google" onclick="event.preventDefault(); document.getElementById('google-disconnect-form').submit();">Disconnect</a>
                    </span>
                </div>
            @else
                <a class="btn btn-google" href="{{ action('SocialAuthController@redirect', ['google']) }}" onclick="event.preventDefault(); document.getElementById('google-connect-form').submit();">
                    @icon('google') Connect
                </a>
            @endif

            </div>
        </div>
    </div>

    <form action="{{ action('SocialAuthController@disconnect', 'google') }}" method="POST" id="google-disconnect-form" style="display: none">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>
    <form action="{{ action('SocialAuthController@redirect', 'google') }}" method="POST" id="google-connect-form" style="display: none">
        {{ csrf_field() }}
    </form>
@stop
