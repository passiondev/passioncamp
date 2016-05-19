@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Edit User</h1>
        </header>

        {{ Form::model($user_data, ['route' => ['user.update', $user], 'method' => 'PUT', 'class' => 'ui form']) }}

            @include('user.partials.form', ['action_text' => 'Update User'])

        {{ Form::close() }}
    </div>
@stop

