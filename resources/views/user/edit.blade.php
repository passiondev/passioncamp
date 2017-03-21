@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Edit User</h1>
        </header>

        <form action="{{ action('UserController@update', $user) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            @include ('user.partials.form')

            <button class="btn btn-primary" type="submit">Update User</button>
        </form>
    </div>
@stop

