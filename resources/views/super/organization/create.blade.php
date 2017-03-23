@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Church</h1>
        </header>

        <form action="{{ action('OrganizationController@store') }}" method="POST">
            {{ csrf_field() }}

            @include('organization.partials.form')

            <button class="btn btn-primary">Create Church</button>
        </form>
    </div>
@stop
