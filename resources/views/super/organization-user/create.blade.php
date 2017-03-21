@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add User</h1>
        </header>

        <form action="{{ action('Super\OrganizationUserController@store', $organization) }}" method="POST">
            {{ csrf_field() }}

            @include ('user.partials.form')

            <button class="btn btn-primary" type="submit">Add User</button>
        </form>

    </div>
@stop

