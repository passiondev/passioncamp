@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Edit User</h1>
        </header>

        {{ Form::model($user_data, ['route' => ['user.update', $user], 'method' => 'PUT']) }}

            @include('user.partials.form', ['action_text' => 'Update User'])

        {{ Form::close() }}
    </div>
@stop

