@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add User</h1>
        </header>

        {{ Form::open(['route' => 'user.store', 'class' => 'ui form']) }}

            @include('user.partials.form', ['action_text' => 'Create User'])

        {{ Form::close() }}
    </div>
@stop

