@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add User</h1>
        </header>

        {{ Form::open(['route' => 'user.store']) }}

            @include('user.partials.form', ['action_text' => 'Create User'])

        {{ Form::close() }}
    </div>
@stop

