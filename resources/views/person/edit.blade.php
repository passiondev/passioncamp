@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Edit Person</h1>
        </header>

        <form action="{{ action('PersonController@update', $person) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            @include ('errors.validation')

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $person->first_name ?? null) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $person->last_name ?? null) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $person->user->email ?? null) }}" class="form-control">
            </div>

            <div class="form-group">
                <label for="email">Phone Number</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone', $person->phone ?? null) }}" class="form-control">
            </div>


            <button class="btn btn-primary" type="submit">Update Person</button>
        </form>
    </div>
@stop

