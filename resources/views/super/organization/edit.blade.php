@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Update Church</h1>
        </header>

        <form action="{{ action('OrganizationController@update', $organization) }}" method="POST">
            {{ method_field('PUT') }}
            {{ csrf_field() }}

            @include('organization.partials.form')

            <button class="btn btn-primary">Update Church</button>
        </form>
    </div>
@stop
