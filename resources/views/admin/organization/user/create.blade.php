@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Auth User</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.user.store', $organization]]) }}

            <div class="form-group">
                {{ Form::label('first_name', 'First Name', ['class' => 'control-label']) }}
                {{ Form::text('first_name', null, ['id' => 'first_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('last_name', 'Name', ['class' => 'control-label']) }}
                {{ Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email Address', ['class' => 'control-label']) }}
                {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) }}
            </div>
            <div class="form-group form-actions">
                <button class="btn btn-primary">Submit</button>
            </div>

        {{ Form::close() }}

    </div>
@stop
