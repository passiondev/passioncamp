@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Admin User</h1>
        </header>

        <form action="{{ action('Account\UserController@store') }}" method="POST">
            {{ csrf_field() }}

            @include ('user.partials.form')

            <button class="btn btn-primary" type="submit">Add User</button>
        </form>

    </div>
@stop

